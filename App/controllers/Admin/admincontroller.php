<?php
namespace App\controllers\Admin;

use App\controllers\BaseController;
use App\models\Product;
use App\models\User;
use App\models\Order;

class AdminController extends BaseController
{
    public function dashboard(): void
    {
        $product = new Product();
        $user = new User();
        $order = new Order();

        $totalProducts = count($product->all());
        $totalUsers = count($user->all());
        $totalOrders = count($order->all('paid'));
        $totalRevenue = $order->totalRevenue();

        $salesDaily = $order->salesByDay(30);
        $salesMonthly = $order->salesByMonth(12);
        $salesYearly = $order->salesByYear(5);

        $topProducts = $order->topProducts(5);

        $this->view('admin/dashboard', [
            'title' => 'Dashboard Admin',
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'salesDaily' => $salesDaily,
            'salesMonthly' => $salesMonthly,
            'salesYearly' => $salesYearly,
            'topProducts' => $topProducts,
        ]);
    }

    /**
     * Ambil semua data laporan sesuai range yang dipilih.
     * Dipakai bareng oleh downloadReport() (PDF) dan downloadReportExcel().
     */
    private function buildReportData(string $range): array
    {
        if (!in_array($range, ['daily', 'monthly', 'yearly'])) {
            $range = 'daily';
        }

        $order = new Order();

        $rangeLabel = match ($range) {
            'monthly' => 'Bulanan — 12 Bulan Terakhir',
            'yearly' => 'Tahunan — 5 Tahun Terakhir',
            default => 'Harian — 30 Hari Terakhir',
        };

        $salesData = match ($range) {
            'monthly' => $order->salesByMonth(12),
            'yearly' => $order->salesByYear(5),
            default => $order->salesByDay(30),
        };

        $dateFrom = match ($range) {
            'monthly' => date('Y-m-d', strtotime('-12 months')),
            'yearly' => date('Y-m-d', strtotime('-5 years')),
            default => date('Y-m-d', strtotime('-30 days')),
        };
        $dateTo = date('Y-m-d');

        $totalRevenue = array_sum(array_column($salesData, 'revenue'));
        $totalOrdersInRange = array_sum(array_column($salesData, 'order_count'));
        $topProducts = $order->topProducts(10);
        $transactions = $order->paidOrdersBetween($dateFrom, $dateTo);

        return compact(
            'range',
            'rangeLabel',
            'salesData',
            'totalRevenue',
            'totalOrdersInRange',
            'topProducts',
            'transactions'
        );
    }

    /**
     * GET /admin/reports/download?range=daily|monthly|yearly
     * Generate & download laporan penjualan dalam bentuk PDF.
     */
    public function downloadReport(): void
    {
        $range = $_GET['range'] ?? 'daily';
        extract($this->buildReportData($range));

        ob_start();
        require ROOT . '/app/views/admin/reports/pdf-template.php';
        $html = ob_get_clean();

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Laporan-Penjualan-' . $range . '-' . date('Y-m-d') . '.pdf';
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }

    /**
     * GET /admin/reports/download-excel?range=daily|monthly|yearly
     * Generate & download laporan penjualan dalam bentuk Excel (.xlsx).
     */
    public function downloadReportExcel(): void
    {
        $range = $_GET['range'] ?? 'daily';
        extract($this->buildReportData($range));

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // ===== Sheet 1: Ringkasan =====
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Ringkasan');
        $sheet->setCellValue('A1', 'Laporan Penjualan — Mizu Design');
        $sheet->setCellValue('A2', 'Periode: ' . $rangeLabel);
        $sheet->setCellValue('A3', 'Dicetak: ' . date('d M Y, H:i'));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A5', 'Total Pendapatan');
        $sheet->setCellValue('B5', $totalRevenue);
        $sheet->setCellValue('A6', 'Jumlah Order Terbayar');
        $sheet->setCellValue('B6', $totalOrdersInRange);
        $sheet->setCellValue('A7', 'Rata-rata per Order');
        $sheet->setCellValue('B7', $totalOrdersInRange > 0 ? round($totalRevenue / $totalOrdersInRange) : 0);
        $sheet->getStyle('A5:A7')->getFont()->setBold(true);

        // ===== Sheet 2: Rincian Penjualan =====
        $sheetSales = $spreadsheet->createSheet();
        $sheetSales->setTitle('Rincian Penjualan');
        $periodLabel = $range === 'yearly' ? 'Tahun' : ($range === 'monthly' ? 'Bulan' : 'Tanggal');
        $sheetSales->fromArray([$periodLabel, 'Jumlah Order', 'Pendapatan'], null, 'A1');
        $sheetSales->getStyle('A1:C1')->getFont()->setBold(true);

        $row = 2;
        foreach ($salesData as $s) {
            if ($range === 'daily') {
                $label = date('d M Y', strtotime($s['date']));
            } elseif ($range === 'monthly') {
                $label = date('F Y', strtotime($s['period'] . '-01'));
            } else {
                $label = $s['period'];
            }
            $sheetSales->setCellValue('A' . $row, $label);
            $sheetSales->setCellValue('B' . $row, (int) $s['order_count']);
            $sheetSales->setCellValue('C' . $row, (float) $s['revenue']);
            $row++;
        }

        // ===== Sheet 3: Produk Terlaris =====
        $sheetProd = $spreadsheet->createSheet();
        $sheetProd->setTitle('Produk Terlaris');
        $sheetProd->fromArray(['Produk', 'Jumlah Terjual', 'Total Pendapatan'], null, 'A1');
        $sheetProd->getStyle('A1:C1')->getFont()->setBold(true);

        $row = 2;
        foreach ($topProducts as $p) {
            $sheetProd->setCellValue('A' . $row, $p['title']);
            $sheetProd->setCellValue('B' . $row, (int) $p['sold_count']);
            $sheetProd->setCellValue('C' . $row, (float) $p['revenue']);
            $row++;
        }

        // ===== Sheet 4: Detail Transaksi + Bukti Pembayaran =====
        $sheetTrx = $spreadsheet->createSheet();
        $sheetTrx->setTitle('Detail Transaksi');
        $sheetTrx->fromArray(
            ['Invoice', 'Pembeli', 'Email', 'Total', 'Tanggal', 'Ada Bukti Bayar?', 'Nama File Bukti'],
            null,
            'A1'
        );
        $sheetTrx->getStyle('A1:G1')->getFont()->setBold(true);

        $row = 2;
        foreach ($transactions as $t) {
            $sheetTrx->setCellValue('A' . $row, $t['invoice']);
            $sheetTrx->setCellValue('B' . $row, $t['customer_name']);
            $sheetTrx->setCellValue('C' . $row, $t['customer_email']);
            $sheetTrx->setCellValue('D' . $row, (float) $t['total']);
            $sheetTrx->setCellValue('E' . $row, date('d M Y H:i', strtotime($t['created_at'])));
            $sheetTrx->setCellValue('F' . $row, !empty($t['payment_proof']) ? 'Ya' : 'Tidak');
            $sheetTrx->setCellValue('G' . $row, $t['payment_proof'] ?? '-');
            $row++;
        }

        // Auto-width semua kolom di semua sheet
        foreach ($spreadsheet->getAllSheets() as $s) {
            foreach (range('A', 'G') as $col) {
                $s->getColumnDimension($col)->setAutoSize(true);
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Laporan-Penjualan-' . $range . '-' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}