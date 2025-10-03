<?php
include '../includes/config.php';
include '../includes/auth.php';
requireAdminAuth();
?>

<?php
// Ambil data statistik
$total_orders = $pdo->query("SELECT COUNT(*) FROM pesanan")->fetchColumn();
$pending_orders = $pdo->query("SELECT COUNT(*) FROM pesanan WHERE status = 'menunggu'")->fetchColumn();
$total_menu = $pdo->query("SELECT COUNT(*) FROM menu WHERE tersedia = 1")->fetchColumn();
$total_revenue = $pdo->query("SELECT COALESCE(SUM(total_harga), 0) FROM pesanan WHERE status = 'selesai'")->fetchColumn();

// Ambil pesanan terbaru
$recent_orders = $pdo->query("
    SELECT p.*, COUNT(d.id) as item_count 
    FROM pesanan p 
    LEFT JOIN detail_pesanan d ON p.id = d.pesanan_id 
    GROUP BY p.id 
    ORDER BY p.created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Warung Mama Eryan</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: 250px;
            background: var(--secondary);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .admin-logo {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .admin-logo h2 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
        }
        
        .admin-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: var(--transition);
            border-left: 4px solid transparent;
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.1);
            border-left-color: var(--primary);
        }
        
        .admin-main {
            flex: 1;
            margin-left: 250px;
            background: #f8f9fa;
        }
        
        .admin-header {
            background: white;
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-content {
            padding: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary);
        }
        
        .stat-card h3 {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary);
        }
        
        .recent-orders {
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        .section-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .orders-table th,
        .orders-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        .orders-table th {
            background: var(--light);
            font-weight: 600;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-menunggu { background: #fff3cd; color: #856404; }
        .status-diproses { background: #cce7ff; color: #004085; }
        .status-selesai { background: #d4edda; color: #155724; }
        .status-dibatalkan { background: #f8d7da; color: #721c24; }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logout-btn {
            background: var(--danger);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
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
                <a href="dashboard.php" class="nav-item active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="menu.php" class="nav-item">
                    <i class="fas fa-utensils"></i> Kelola Menu
                </a>
                <a href="orders.php" class="nav-item">
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
                <h1>Dashboard Admin</h1>
                <div class="user-info">
                    <span>Halo, <?= htmlspecialchars($_SESSION['admin_name']) ?></span>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </header>

            <div class="admin-content">
                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Pesanan</h3>
                        <div class="stat-number"><?= $total_orders ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Pesanan Menunggu</h3>
                        <div class="stat-number"><?= $pending_orders ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Menu</h3>
                        <div class="stat-number"><?= $total_menu ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Pendapatan</h3>
                        <div class="stat-number">Rp <?= number_format($total_revenue, 0, ',', '.') ?></div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="recent-orders">
                    <div class="section-header">
                        <h2>Pesanan Terbaru</h2>
                        <a href="orders.php" class="btn btn-primary">Lihat Semua</a>
                    </div>
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
                            <?php foreach ($recent_orders as $order): ?>
                            <tr>
                                <td>#<?= $order['id'] ?></td>
                                <td><?= htmlspecialchars($order['nama_pelanggan']) ?></td>
                                <td><?= htmlspecialchars($order['telepon']) ?></td>
                                <td>Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="status-badge status-<?= $order['status'] ?>">
                                        <?= ucfirst($order['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                <td>
                                    <a href="orders.php?action=view&id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/script.js"></script>
</body>
</html>