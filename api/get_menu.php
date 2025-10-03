<?php
header('Content-Type: application/json');
include '../includes/config.php';

try {
    $whereClause = "WHERE tersedia = 1";
    $params = [];

    // Jika ada parameter ID, ambil menu tertentu
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $whereClause = "WHERE id = ? AND tersedia = 1";
        $params[] = $_GET['id'];
        
        $stmt = $pdo->prepare("SELECT * FROM menu $whereClause");
        $stmt->execute($params);
        $menu = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($menu) {
            echo json_encode([
                'success' => true,
                'data' => $menu
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Menu tidak ditemukan'
            ]);
        }
        exit;
    }

    // Ambil semua menu
    $stmt = $pdo->prepare("SELECT * FROM menu $whereClause ORDER BY kategori, nama_menu");
    $stmt->execute($params);
    $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $menuItems
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
?>