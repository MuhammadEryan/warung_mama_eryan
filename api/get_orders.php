<?php
// api/get_orders.php - PAKAI $pdo ANDA
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
error_reporting(0);
ini_set('display_errors', 0);

// Include config Anda yang pakai $pdo
include_once '../includes/config.php';

// Sekarang pakai $pdo yang sudah ada
if (!isset($pdo)) {
    echo json_encode(["success" => false, "message" => "Koneksi database tidak ditemukan"]);
    exit;
}

$phone = $_GET['phone'] ?? '';
$phone = preg_replace('/[^0-9]/', '', $phone);

if (empty($phone)) {
    echo json_encode(["success" => false, "message" => "Nomor telepon kosong"]);
    exit;
}

try {
    // QUERY LANGSUNG PAKAI $pdo
    $query = "SELECT * FROM pesanan WHERE telepon = :phone ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":phone", $phone);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $orders = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $orders[] = [
                "id" => $row['id'],
                "customer_name" => $row['nama_pelanggan'],
                "customer_phone" => $row['telepon'],
                "customer_address" => $row['alamat'],
                "order_notes" => $row['catatan'],
                "total_amount" => (float)$row['harga'],
                "status" => $row['status'],
                "created_at" => $row['created_at'],
                "items" => [
                    [
                        "name" => "Pesanan", 
                        "price" => (float)$row['harga'],
                        "quantity" => 1
                    ]
                ]
            ];
        }
        
        echo json_encode(["success" => true, "data" => $orders]);
        
    } else {
        echo json_encode(["success" => false, "message" => "Tidak ada pesanan ditemukan"]);
    }
    
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
}
?>