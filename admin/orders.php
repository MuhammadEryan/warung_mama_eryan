<?php
include '../includes/config.php';
include '../includes/auth.php';
requireAdminAuth();

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE pesanan SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $order_id]);
    $success = "Status pesanan berhasil diupdate!";
}

// Ambil semua pesanan
$orders = $pdo->query("
    SELECT p.*, COUNT(d.id) as item_count 
    FROM pesanan p 
    LEFT JOIN detail_pesanan d ON p.id = d.pesanan_id 
    GROUP BY p.id 
    ORDER BY p.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Jika ada parameter view, ambil detail pesanan
$order_details = [];
if (isset($_GET['action']) && $_GET['action'] === 'view' && isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $order_details = $pdo->prepare("
        SELECT d.*, m.nama_menu 
        FROM detail_pesanan d 
        JOIN menu m ON d.menu_id = m.id 
        WHERE d.pesanan_id = ?
    ")->execute([$order_id]);
    $order_details = $pdo->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Warung Mama Eryan</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 250px; background: var(--secondary); color: white; position: fixed; height: 100vh; }
        .admin-main { flex: 1; margin-left: 250px; background: #f8f9fa; }
        .admin-header { background: white; padding: 1rem 2rem; box-shadow: var(--shadow); }
        .admin-content { padding: 2rem; }
        
        .orders-table { width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: var(--shadow); }
        .orders-table th, .orders-table td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border); }
        .orders-table th { background: var(--light); font-weight: 600; }
        
        .status-form { display: flex; gap: 0.5rem; align-items: center; }
        .status-select { padding: 0.25rem 0.5rem; border: 1px solid var(--border); border-radius: 5px; }
        
        .order-details { background: white; border-radius: 10px; box-shadow: var(--shadow); padding: 1.5rem; margin-bottom: 2rem; }
        .detail-item { display: flex; justify-content: between; margin-bottom: 0.5rem; }
        .detail-label { font-weight: 600; width: 150px; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        .items-table th, .items-table td { padding: 0.75rem; text-align: left; border-bottom: 1px solid var(--border); }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="admin-logo">
                <h2><i class="fas fa-utensils"></i> Warung Mama Eryan</h2>
            </div>
            <nav class="admin-nav">
                <a href="dashboard.php" class="nav-item">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="menu.php" class="nav-item">
                    <i class="fas fa-utensils"></i> Kelola Menu
                </a>
                <a href="orders.php" class="nav-item active">
                    <i class="fas fa-shopping-cart"></i> Pesanan
                </a>
                <a href="logout.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="admin-main">
            <header class="admin-header">
                <h1>Kelola Pesanan</h1>
                <div class="user-info">
                    <span>Halo, <?= htmlspecialchars($_SESSION['admin_name']) ?></span>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>

            <div class="admin-content">
                <?php if (isset($success)): ?>
                    <div class="notification success show"><?= $success ?></div>
                <?php endif; ?>

                <!-- Order Details Modal -->
                <?php if (isset($_GET['action']) && $_GET['action'] === 'view' && isset($_GET['id'])): ?>
                    <?php
                    $order = $pdo->prepare("SELECT * FROM pesanan WHERE id = ?")->execute([$_GET['id']]);
                    $order = $pdo->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div class="order-details">
                        <h2>Detail Pesanan #<?= $order['id'] ?></h2>
                        
                        <div class="detail-item">
                            <span class="detail-label">Nama Pelanggan:</span>
                            <span><?= htmlspecialchars($order['nama_pelanggan']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Telepon:</span>
                            <span><?= htmlspecialchars($order['telepon']) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Alamat:</span>
                            <span><?= htmlspecialchars($order['alamat']) ?: '-' ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Catatan:</span>
                            <span><?= htmlspecialchars($order['catatan']) ?: '-' ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Harga:</span>
                            <span>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Status:</span>
                            <span class="status-badge status-<?= $order['status'] ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tanggal Pesan:</span>
                            <span><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                        </div>

                        <h3>Items Pesanan:</h3>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Quantity</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_details as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['nama_menu']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                    <td>Rp <?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <div style="margin-top: 1rem;">
                            <a href="orders.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Orders Table -->
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Telepon</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['nama_pelanggan']) ?></td>
                            <td><?= htmlspecialchars($order['telepon']) ?></td>
                            <td>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <form method="POST" class="status-form">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="status" class="status-select" onchange="this.form.submit()">
                                        <option value="menunggu" <?= $order['status'] === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                        <option value="diproses" <?= $order['status'] === 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                        <option value="selesai" <?= $order['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                        <option value="dibatalkan" <?= $order['status'] === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td>
                                <a href="orders.php?action=view&id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>