<?php
$activeMenu = 'dashboard';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Dashboard</h1>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <div class="admin-card">
      <div class="text-secondary small mb-1">Total Produk</div>
      <div class="fs-3 fw-bold"><?= $totalProducts ?></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="admin-card">
      <div class="text-secondary small mb-1">Total User</div>
      <div class="fs-3 fw-bold"><?= $totalUsers ?></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="admin-card">
      <div class="text-secondary small mb-1">Order Terbayar</div>
      <div class="fs-3 fw-bold"><?= $totalOrders ?></div>
    </div>
  </div>
  <div class="col-6 col-md-3">
    <div class="admin-card">
      <div class="text-secondary small mb-1">Total Pendapatan</div>
      <div class="fs-3 fw-bold text-primary">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">

  <!-- Chart Penjualan -->
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h6 class="fw-bold mb-0">Grafik Penjualan</h6>
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-primary chart-range-btn active"
              data-range="daily">Harian</button>
            <button type="button" class="btn btn-outline-primary chart-range-btn" data-range="monthly">Bulanan</button>
            <button type="button" class="btn btn-outline-primary chart-range-btn" data-range="yearly">Tahunan</button>
          </div>
          <a id="downloadReportBtn" href="<?= BASE_URL ?>/admin/reports/download?range=daily"
            class="btn btn-sm btn-admin-primary">
            <i class="ti ti-file-download"></i> Unduh Laporan
          </a>
          <a id="downloadReportBtn" href="<?= BASE_URL ?>/admin/reports/download?range=daily"
            class="btn btn-sm btn-admin-primary">
            <i class="ti ti-file-download"></i> PDF
          </a>
          <a id="downloadExcelBtn" href="<?= BASE_URL ?>/admin/reports/download-excel?range=daily"
            class="btn btn-sm btn-outline-primary">
            <i class="ti ti-file-spreadsheet"></i> Excel
          </a>
        </div>
      </div>

      <?php if (empty($salesDaily) && empty($salesMonthly) && empty($salesYearly)): ?>
        <p class="text-secondary small mb-0 py-5 text-center">Belum ada data penjualan.</p>
      <?php else: ?>
        <canvas id="salesChart" height="110"></canvas>
      <?php endif; ?>
    </div>
  </div>

  <!-- Produk Terlaris -->
  <div class="col-lg-4">
    <div class="admin-card h-100">
      <h6 class="fw-bold mb-3">Produk Terlaris</h6>
      <?php if (empty($topProducts)): ?>
        <p class="text-secondary small mb-0">Belum ada penjualan yang tercatat.</p>
      <?php else: ?>
        <?php foreach ($topProducts as $i => $p): ?>
          <div class="d-flex align-items-center gap-2 <?= $i > 0 ? 'mt-3 pt-3 border-top' : '' ?>">
            <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>"
              style="width:42px;height:42px;object-fit:cover;border-radius:8px;background:#f1f5f9">
            <div class="flex-grow-1" style="min-width:0">
              <div class="small fw-semibold text-truncate"><?= htmlspecialchars($p['title']) ?></div>
              <div class="small text-secondary"><?= (int) $p['sold_count'] ?>x terjual</div>
            </div>
            <div class="small fw-bold text-primary text-nowrap">
              Rp <?= number_format($p['revenue'], 0, ',', '.') ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

</div>

<?php if (!empty($salesDaily) || !empty($salesMonthly) || !empty($salesYearly)): ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
  <script>
    // Data mentah dari server untuk ketiga rentang waktu
    const rawData = {
      daily: <?= json_encode($salesDaily) ?>,
      monthly: <?= json_encode($salesMonthly) ?>,
      yearly: <?= json_encode($salesYearly) ?>,
    };

    // Ubah data mentah jadi format {labels, revenues} sesuai rentang yang dipilih
    function buildChartData(range) {
      const data = rawData[range] || [];

      const labels = data.map(d => {
        if (range === 'daily') {
          const date = new Date(d.date);
          return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short'
          });
        }
        if (range === 'monthly') {
          const [year, month] = d.period.split('-');
          const date = new Date(year, month - 1, 1);
          return date.toLocaleDateString('id-ID', {
            month: 'short',
            year: '2-digit'
          });
        }
        // yearly
        return String(d.period);
      });

      const revenues = data.map(d => parseFloat(d.revenue));

      return {
        labels,
        revenues
      };
    }

    const ctx = document.getElementById('salesChart');
    let chart = null;

    function renderChart(range) {
      const {
        labels,
        revenues
      } = buildChartData(range);

      if (chart) {
        chart.data.labels = labels;
        chart.data.datasets[0].data = revenues;
        chart.update();
        return;
      }

      chart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Pendapatan',
            data: revenues,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.08)',
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#2563eb',
            pointRadius: 4,
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: (ctx) => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: (value) => 'Rp ' + (value / 1000) + 'rb'
              }
            }
          }
        }
      });
    }

    // Render awal: harian
    if (ctx) {
      renderChart('daily');
    }

    // Toggle tombol
    document.querySelectorAll('.chart-range-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.chart-range-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderChart(btn.dataset.range);

        const downloadBtn = document.getElementById('downloadReportBtn');
        if (downloadBtn) downloadBtn.href = '<?= BASE_URL ?>/admin/reports/download?range=' + btn
          .dataset.range;

        const downloadExcelBtn = document.getElementById('downloadExcelBtn');
        if (downloadExcelBtn) downloadExcelBtn.href =
          '<?= BASE_URL ?>/admin/reports/download-excel?range=' + btn.dataset.range;
      });
    });
  </script>
<?php endif; ?>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>