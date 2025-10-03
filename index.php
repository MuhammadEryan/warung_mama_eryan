<?php
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warung Mama Eryan - Rasa Autentik Nusantara</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <div class="logo-placeholder">
                    <i class="fas fa-utensils"></i>
                </div>
                <span>Warung Mama Eryan</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#home" class="nav-link">Beranda</a>
                </li>
                <li class="nav-item">
                    <a href="#menu" class="nav-link">Menu</a>
                </li>
                <li class="nav-item">
                    <a href="#about" class="nav-link">Tentang</a>
                </li>
                <li class="nav-item">
                    <a href="#contact" class="nav-link">Kontak</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" id="track-order-btn">
                        <i class="fas fa-truck"></i> Lacak Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin/login.php" class="nav-link admin-btn">
                        <i class="fas fa-user-shield"></i> Admin
                    </a>
                </li>
                <li class="nav-item">
                    <button class="cart-btn" id="cart-toggle">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count">0</span>
                    </button>
                </li>
            </ul>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </header>

    <!-- Shopping Cart Sidebar -->
    <div class="cart-sidebar" id="cart-sidebar">
        <div class="cart-header">
            <h3><i class="fas fa-shopping-cart"></i> Keranjang Pesanan</h3>
            <button class="close-cart" id="close-cart">&times;</button>
        </div>
        <div class="cart-items" id="cart-items">
            <div class="empty-cart">Keranjang kosong</div>
        </div>
        <div class="cart-footer">
            <div class="cart-total">
                <strong>Total: Rp <span id="cart-total">0</span></strong>
            </div>
            <button class="checkout-btn" id="checkout-btn">Pesan Sekarang</button>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1 class="hero-title">Warung <span>Mama Eryan</span></h1>
            <p class="hero-subtitle">Rasa Autentik Nusantara sejak 2010</p>
            <div class="hero-stats">
                <div class="stat">
                    <span class="number">13+</span>
                    <span class="label">Tahun</span>
                </div>
                <div class="stat">
                    <span class="number">50+</span>
                    <span class="label">Menu</span>
                </div>
                <div class="stat">
                    <span class="number">1000+</span>
                    <span class="label">Pelanggan</span>
                </div>
            </div>
            <div class="hero-actions">
                <a href="#menu" class="btn btn-primary">
                    <i class="fas fa-utensils"></i> Pesan Sekarang
                </a>
                <a href="#about" class="btn btn-secondary">
                    <i class="fas fa-play"></i> Lihat Cerita Kami
                </a>
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="menu-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Menu Favorit</h2>
                <p class="section-subtitle">Pilihan terbaik dari dapur Mama Eryan</p>
            </div>
            
            <div class="menu-categories">
                <button class="category-btn active" data-category="all">
                    <i class="fas fa-th-large"></i> Semua
                </button>
                <button class="category-btn" data-category="makanan">
                    <i class="fas fa-utensils"></i> Makanan
                </button>
                <button class="category-btn" data-category="minuman">
                    <i class="fas fa-glass-whiskey"></i> Minuman
                </button>
                <button class="category-btn" data-category="snack">
                    <i class="fas fa-cookie"></i> Snack
                </button>
            </div>

            <div class="menu-grid" id="menu-grid">
                <div class="loading">
                    <div class="spinner"></div>
                    <p>Memuat menu...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Banner -->
    <section class="promo-banner">
        <div class="container">
            <div class="banner-content">
                <h3>🔥 Promo Spesial Hari Ini!</h3>
                <p>Gratis 1 Es Teh Manis untuk pembelian minimal Rp 20.000</p>
                <span class="promo-code">Kode: MAMAGOKIL</span>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2 class="section-title">Cerita Warung Kami</h2>
                    <p>Sejak 2010, Warung Mama Eryan telah menjadi tempat favorit bagi pencinta kuliner autentik. Dengan resep turun-temurun dan bahan-bahan pilihan, kami menghadirkan cita rasa yang membuat Anda selalu rindu untuk kembali.</p>
                    
                    <div class="features-grid">
                        <div class="feature">
                            <i class="fas fa-clock"></i>
                            <h4>Buka Setiap Hari</h4>
                            <p>08:00 - 22:00 WIB</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-truck"></i>
                            <h4>Pesan Antar</h4>
                            <p>Gratis ongkir area sekitar</p>
                        </div>
                        <div class="feature">
                            <i class="fas fa-utensils"></i>
                            <h4>Masakan Rumahan</h4>
                            <p>Rasa autentik keluarga</p>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <div class="image-placeholder">
                        <i class="fas fa-store"></i>
                        <p>Tampilan Warung Kami</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="testimonial-section">
        <div class="container">
            <h2 class="section-title">Apa Kata Pelanggan</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Seblaknya enak banget! Rasanya autentik dan porsinya generous. Sudah langganan dari dulu."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <strong>Budi Santoso</strong>
                            <span>Pelanggan Setia</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>"Es teh manisnya pas banget, tidak terlalu manis. Pelayanan cepat dan ramah. Recommended!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <strong>Sari Dewi</strong>
                            <span>Karyawan Kantoran</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="contact-content">
                <div class="contact-info">
                    <h2 class="section-title">Hubungi Kami</h2>
                    
                    <div class="contact-methods">
                        <div class="contact-method">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <h4>Alamat</h4>
                                <p>Jl. Raya Contoh No. 123<br>Jakarta Selatan 12540</p>
                            </div>
                        </div>
                        <div class="contact-method">
                            <i class="fas fa-phone"></i>
                            <div>
                                <h4>Telepon</h4>
                                <p>(021) 1234-5678<br>0812-3456-7890</p>
                            </div>
                        </div>
                        <div class="contact-method">
                            <i class="fas fa-clock"></i>
                            <div>
                                <h4>Jam Buka</h4>
                                <p>Setiap Hari<br>08:00 - 22:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <h3>Kirim Pesan</h3>
                    <form id="contact-form">
                        <div class="form-group">
                            <input type="text" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <input type="tel" placeholder="Nomor Telepon" required>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Pesan Anda" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i> Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Warung Mama Eryan</h3>
                    <p>Rasa autentik yang membuat Anda kangen sejak 2010.</p>
                </div>
                <div class="footer-section">
                    <h3>Link Cepat</h3>
                    <ul>
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#menu">Menu</a></li>
                        <li><a href="#about">Tentang Kami</a></li>
                        <li><a href="#contact">Kontak</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Follow Kami</h3>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                        <a href="#"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 Warung Mama Eryan. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Order Modal -->
    <div class="modal" id="order-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Form Pemesanan</h3>
                <button class="close-modal">&times;</button>
            </div>
            <form id="order-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" id="customer-name" required>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon *</label>
                        <input type="tel" id="customer-phone" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat Pengiriman</label>
                        <textarea id="customer-address" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Catatan Pesanan</label>
                        <textarea id="order-notes" rows="3" placeholder="Contoh: Pedas, tidak pakai sayur, dll."></textarea>
                    </div>
                </div>
                <div class="order-summary">
                    <h4>Ringkasan Pesanan</h4>
                    <div id="order-summary-items"></div>
                    <div class="order-total">
                        <strong>Total: Rp <span id="order-total">0</span></strong>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" id="cancel-order">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Konfirmasi Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tracking Modal -->
    <div class="modal" id="tracking-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Lacak Pesanan</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="tracking-content">
                <div class="tracking-form">
                    <div class="form-group">
                        <label>Masukkan Nomor Telepon</label>
                        <input type="tel" id="tracking-phone" placeholder="Contoh: 08123456789" required>
                    </div>
                    <button class="btn btn-primary btn-block" id="track-order">
                        <i class="fas fa-search"></i> Cari Pesanan
                    </button>
                </div>
                
                <div class="tracking-result" id="tracking-result" style="display: none;">
                    <div class="order-status">
                        <h4>Status Pesanan</h4>
                        <div class="status-timeline">
                            <div class="status-step active">
                                <div class="step-icon">📝</div>
                                <div class="step-info">
                                    <strong>Pesanan Dibuat</strong>
                                    <span id="order-time">-</span>
                                </div>
                            </div>
                            <div class="status-step" id="step-processing">
                                <div class="step-icon">👨‍🍳</div>
                                <div class="step-info">
                                    <strong>Sedang Diproses</strong>
                                    <span>-</span>
                                </div>
                            </div>
                            <div class="status-step" id="step-ready">
                                <div class="step-icon">✅</div>
                                <div class="step-info">
                                    <strong>Siap Diantar</strong>
                                    <span>-</span>
                                </div>
                            </div>
                            <div class="status-step" id="step-delivered">
                                <div class="step-icon">🚗</div>
                                <div class="step-info">
                                    <strong>Sedang Diantar</strong>
                                    <span>-</span>
                                </div>
                            </div>
                            <div class="status-step" id="step-completed">
                                <div class="step-icon">🎉</div>
                                <div class="step-info">
                                    <strong>Selesai</strong>
                                    <span>-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="order-details">
                        <h4>Detail Pesanan</h4>
                        <div class="detail-item">
                            <strong>No. Pesanan:</strong>
                            <span id="detail-order-id">-</span>
                        </div>
                        <div class="detail-item">
                            <strong>Nama:</strong>
                            <span id="detail-customer-name">-</span>
                        </div>
                        <div class="detail-item">
                            <strong>Telepon:</strong>
                            <span id="detail-customer-phone">-</span>
                        </div>
                        <div class="detail-item">
                            <strong>Alamat:</strong>
                            <span id="detail-customer-address">-</span>
                        </div>
                        <div class="detail-item">
                            <strong>Total:</strong>
                            <span id="detail-total-amount">-</span>
                        </div>
                        
                        <div class="order-items">
                            <strong>Items:</strong>
                            <div id="detail-order-items"></div>
                        </div>
                    </div>
                    
                    <div class="tracking-actions">
                        <button class="btn btn-secondary" id="track-another">Cari Pesanan Lain</button>
                        <button class="btn btn-primary" id="refresh-status">
                            <i class="fas fa-sync-alt"></i> Refresh Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <script src="js/script.js"></script>
</body>
</html>