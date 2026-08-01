<?php
/**
 * SCRIPT DIAGNOSTIK SEMENTARA -- hapus file ini setelah selesai debugging.
 * Tujuan: tes apakah Server Key Midtrans valid, TANPA lewat SDK/kode project,
 * supaya kita tahu pasti apakah masalahnya di key atau di kode.
 *
 * Cara pakai: taruh file ini di public/test-midtrans.php,
 * lalu buka: http://localhost/mizu-design/public/test-midtrans.php
 */

// Load .env manual (sama seperti index.php)
$env = parse_ini_file(__DIR__ . '/../.env');
$serverKey = $env['MIDTRANS_SERVER_KEY'] ?? '';

echo "<h3>1. Server Key yang dipakai:</h3>";
echo "<pre>" . htmlspecialchars($serverKey) . "</pre>";
echo "<p>Panjang: " . strlen($serverKey) . " karakter</p>";

echo "<h3>2. Mengirim request test ke Midtrans Sandbox...</h3>";

$payload = json_encode([
    'transaction_details' => [
        'order_id' => 'TEST-' . time(),
        'gross_amount' => 10000,
    ],
]);

$ch = curl_init('https://app.sandbox.midtrans.com/snap/v1/transactions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Basic ' . base64_encode($serverKey . ':'),
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "<h3>3. Hasil:</h3>";
echo "<p><strong>HTTP Status Code:</strong> " . $httpCode . "</p>";

if ($curlError) {
    echo "<p style='color:red'><strong>cURL Error:</strong> " . htmlspecialchars($curlError) . "</p>";
}

echo "<p><strong>Response dari Midtrans:</strong></p>";
echo "<pre style='background:#f3f4f6;padding:12px;border-radius:8px'>" . htmlspecialchars($response) . "</pre>";

if ($httpCode === 201) {
    echo "<p style='color:green;font-weight:bold'>✅ BERHASIL! Server Key valid dan bisa generate Snap Token.</p>";
} elseif ($httpCode === 401) {
    echo "<p style='color:red;font-weight:bold'>❌ GAGAL (401) -- Server Key ditolak Midtrans. Key ini tidak valid / salah copy / sudah diregenerate.</p>";
} else {
    echo "<p style='color:orange;font-weight:bold'>⚠️ Status tidak terduga, lihat response di atas untuk detail.</p>";
}
