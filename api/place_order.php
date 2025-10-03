<?php
header('Content-Type: application/json');
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!$input || !isset($input['nama']) || !isset($input['telepon']) || !isset($input['items'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Hitung total harga
    $totalHarga = 0;
    foreach ($input['items'] as $item) {
        $totalHarga += $item['harga'] * $item['quantity'];
    }

    // Insert pesanan
    $stmt = $pdo->prepare("INSERT INTO pesanan (nama_pelanggan, telepon, alamat, total_harga, catatan) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $input['nama'],
        $input['telepon'],
        $input['alamat'] ?? '',
        $totalHarga,
        $input['catatan'] ?? ''
    ]);

    $pesananId = $pdo->lastInsertId();

    // Insert detail pesanan
    $stmt = $pdo->prepare("INSERT INTO detail_pesanan (pesanan_id, menu_id, quantity, harga) VALUES (?, ?, ?, ?)");
    
    foreach ($input['items'] as $item) {
        $stmt->execute([
            $pesananId,
            $item['id'],
            $item['quantity'],
            $item['harga']
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Pesanan berhasil diterima! No. Pesanan: ' . $pesananId,
        'order_id' => $pesananId
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
?>