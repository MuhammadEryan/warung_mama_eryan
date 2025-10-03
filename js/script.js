// Warung Mama Eryan - Main JavaScript
class WarungApp {
    constructor() {
        this.cart = [];
        this.init();
    }

    init() {
        console.log('WarungApp initialized');
        this.loadCart();
        this.setupEventListeners();
        this.updateCartDisplay();
        this.loadMenuItems();
    }

    setupEventListeners() {
        // Cart toggle
        document.getElementById('cart-toggle')?.addEventListener('click', () => {
            this.toggleCart();
        });

        // Close cart
        document.getElementById('close-cart')?.addEventListener('click', () => {
            this.toggleCart();
        });

        // Checkout button
        document.getElementById('checkout-btn')?.addEventListener('click', () => {
            this.showCheckoutModal();
        });

        // Close modal
        document.querySelector('.close-modal')?.addEventListener('click', () => {
            this.hideCheckoutModal();
        });

        // Cancel order
        document.getElementById('cancel-order')?.addEventListener('click', () => {
            this.hideCheckoutModal();
        });

        // Order form submission
        document.getElementById('order-form')?.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitOrder();
        });

        // Menu filter buttons
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.filterMenu(e.target.dataset.category);
                document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
                e.target.classList.add('active');
            });
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    async loadMenuItems() {
        try {
            const response = await fetch('api/get_menu.php');
            const result = await response.json();
            
            if (result.success) {
                this.renderMenu(result.data);
            } else {
                this.showError('Gagal memuat menu');
            }
        } catch (error) {
            console.error('Error loading menu:', error);
            this.showError('Tidak dapat terhubung ke server');
        }
    }

    renderMenu(menuItems) {
        const menuGrid = document.getElementById('menu-grid');
        
        if (menuItems.length === 0) {
            menuGrid.innerHTML = '<div class="no-items">Tidak ada menu yang tersedia</div>';
            return;
        }

        menuGrid.innerHTML = menuItems.map(item => `
            <div class="menu-card" data-category="${item.kategori}">
                <div class="menu-image">
                    <div class="image-placeholder">
                        <i class="fas fa-${this.getMenuIcon(item.kategori)}"></i>
                    </div>
                    <div class="menu-overlay">
                        <button class="btn-add-cart" data-menu-id="${item.id}">
                            <i class="fas fa-cart-plus"></i> Tambah
                        </button>
                    </div>
                </div>
                <div class="menu-content">
                    <h3>${item.nama_menu}</h3>
                    <p>${item.deskripsi || 'Menu spesial Warung Mama Eryan'}</p>
                    <div class="menu-footer">
                        <span class="price">Rp ${this.formatPrice(item.harga)}</span>
                        <span class="category">${this.capitalizeFirst(item.kategori)}</span>
                    </div>
                </div>
            </div>
        `).join('');

        // Setup add to cart buttons after rendering
        this.setupAddToCartButtons();
    }

    setupAddToCartButtons() {
        document.querySelectorAll('.btn-add-cart').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const menuId = e.target.closest('.btn-add-cart').dataset.menuId;
                this.addToCart(parseInt(menuId));
            });
        });
    }

    async addToCart(menuId) {
        try {
            const response = await fetch(`api/get_menu.php?id=${menuId}`);
            const result = await response.json();
            
            if (result.success && result.data) {
                const menu = result.data;
                const existingItem = this.cart.find(item => item.id === menuId);
                
                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    this.cart.push({
                        id: menu.id,
                        nama_menu: menu.nama_menu,
                        harga: parseFloat(menu.harga),
                        quantity: 1
                    });
                }
                
                this.saveCart();
                this.updateCartDisplay();
                this.showNotification(`✅ ${menu.nama_menu} ditambahkan ke keranjang!`, 'success');
                
                // Auto show cart sidebar
                this.toggleCart();
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showNotification('❌ Gagal menambahkan item', 'error');
        }
    }

    removeFromCart(menuId) {
        this.cart = this.cart.filter(item => item.id !== menuId);
        this.saveCart();
        this.updateCartDisplay();
        this.showNotification('Item dihapus dari keranjang', 'warning');
    }

    updateQuantity(menuId, change) {
        const item = this.cart.find(item => item.id === menuId);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                this.removeFromCart(menuId);
            } else {
                this.saveCart();
                this.updateCartDisplay();
            }
        }
    }

    updateCartDisplay() {
        const cartCount = document.querySelector('.cart-count');
        const cartItems = document.getElementById('cart-items');
        const cartTotal = document.getElementById('cart-total');
        
        // Update cart count
        if (cartCount) {
            const totalItems = this.cart.reduce((total, item) => total + item.quantity, 0);
            cartCount.textContent = totalItems;
            cartCount.style.display = totalItems > 0 ? 'flex' : 'none';
        }
        
        // Update cart items
        if (cartItems) {
            if (this.cart.length === 0) {
                cartItems.innerHTML = '<div class="empty-cart">Keranjang kosong</div>';
            } else {
                cartItems.innerHTML = this.cart.map(item => `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.nama_menu}</div>
                            <div class="cart-item-price">Rp ${this.formatPrice(item.harga)}</div>
                        </div>
                        <div class="cart-item-controls">
                            <button class="quantity-btn" onclick="warungApp.updateQuantity(${item.id}, -1)">-</button>
                            <span class="quantity">${item.quantity}</span>
                            <button class="quantity-btn" onclick="warungApp.updateQuantity(${item.id}, 1)">+</button>
                            <button class="remove-btn" onclick="warungApp.removeFromCart(${item.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
            }
        }
        
        // Update total
        if (cartTotal) {
            cartTotal.textContent = this.formatPrice(this.getTotalPrice());
        }
    }

    getTotalPrice() {
        return this.cart.reduce((total, item) => total + (item.harga * item.quantity), 0);
    }

    toggleCart() {
        const cartSidebar = document.getElementById('cart-sidebar');
        if (cartSidebar) {
            cartSidebar.classList.toggle('active');
        }
    }

    showCheckoutModal() {
        if (this.cart.length === 0) {
            this.showNotification('Keranjang masih kosong!', 'warning');
            return;
        }
        
        const modal = document.getElementById('order-modal');
        const orderSummary = document.getElementById('order-summary-items');
        const orderTotal = document.getElementById('order-total');
        
        if (modal && orderSummary) {
            // Update order summary
            orderSummary.innerHTML = this.cart.map(item => `
                <div class="order-item">
                    <span>${item.nama_menu} (${item.quantity}x)</span>
                    <span>Rp ${this.formatPrice(item.harga * item.quantity)}</span>
                </div>
            `).join('');
            
            // Update total
            if (orderTotal) {
                orderTotal.textContent = this.formatPrice(this.getTotalPrice());
            }
            
            modal.classList.add('active');
        }
    }

    hideCheckoutModal() {
        const modal = document.getElementById('order-modal');
        if (modal) {
            modal.classList.remove('active');
        }
    }

    async submitOrder() {
        const form = document.getElementById('order-form');
        const nameInput = document.getElementById('customer-name');
        const phoneInput = document.getElementById('customer-phone');
        
        const orderData = {
            nama: nameInput.value.trim(),
            telepon: phoneInput.value.trim(),
            alamat: document.getElementById('customer-address').value.trim(),
            catatan: document.getElementById('order-notes').value.trim(),
            items: this.cart
        };

        // Validation
        if (!orderData.nama) {
            this.showNotification('Harap isi nama lengkap!', 'error');
            nameInput.focus();
            return;
        }

        if (!orderData.telepon) {
            this.showNotification('Harap isi nomor telepon!', 'error');
            phoneInput.focus();
            return;
        }

        try {
            const response = await fetch('api/place_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('🎉 Pesanan berhasil dikirim! Terima kasih.', 'success');
                this.clearCart();
                this.hideCheckoutModal();
                form.reset();
            } else {
                this.showNotification(result.message || 'Gagal mengirim pesanan', 'error');
            }
        } catch (error) {
            console.error('Error submitting order:', error);
            this.showNotification('❌ Terjadi kesalahan saat mengirim pesanan', 'error');
        }
    }

    filterMenu(category) {
        const menuCards = document.querySelectorAll('.menu-card');
        menuCards.forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Utility functions
    formatPrice(price) {
        return new Intl.NumberFormat('id-ID').format(price);
    }

    capitalizeFirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    getMenuIcon(category) {
        const icons = {
            'makanan': 'utensils',
            'minuman': 'glass-whiskey',
            'snack': 'cookie'
        };
        return icons[category] || 'utensils';
    }

    showNotification(message, type = 'info') {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            
            setTimeout(() => {
                this.hideNotification();
            }, 5000);
        }
    }

    hideNotification() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.classList.remove('show');
        }
    }

    showError(message) {
        const menuGrid = document.getElementById('menu-grid');
        if (menuGrid) {
            menuGrid.innerHTML = `
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${message}</p>
                    <button onclick="location.reload()">Coba Lagi</button>
                </div>
            `;
        }
    }

    // Cart persistence
    saveCart() {
        localStorage.setItem('warung_cart', JSON.stringify(this.cart));
    }

    loadCart() {
        const savedCart = localStorage.getItem('warung_cart');
        if (savedCart) {
            this.cart = JSON.parse(savedCart);
        }
    }

    clearCart() {
        this.cart = [];
        this.saveCart();
        this.updateCartDisplay();
    }
}

// Initialize the app
let warungApp;

document.addEventListener('DOMContentLoaded', () => {
    warungApp = new WarungApp();
});


// ===== TRACKING SYSTEM =====
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Tracking system loaded');
    
    // Event Listener untuk tombol Lacak Pesanan di navbar
    const trackOrderBtn = document.getElementById('track-order-btn');
    if (trackOrderBtn) {
        trackOrderBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('🔍 Tombol Lacak Pesanan diklik');
            document.getElementById('tracking-modal').style.display = 'block';
        });
    }

    // Event Listener untuk tombol Cari Pesanan di modal
    const trackOrder = document.getElementById('track-order');
    if (trackOrder) {
        trackOrder.addEventListener('click', function() {
            const phone = document.getElementById('tracking-phone').value.trim();
            console.log('🔍 Tombol Cari Pesanan diklik, nomor:', phone);
            
            if (!phone) {
                showNotification('Masukkan nomor telepon terlebih dahulu', 'warning');
                return;
            }
            
            searchOrder(phone);
        });
    }

    // Event Listener untuk tombol Refresh Status
    const refreshStatus = document.getElementById('refresh-status');
    if (refreshStatus) {
        refreshStatus.addEventListener('click', function() {
            const phone = document.getElementById('tracking-phone').value.trim();
            if (phone) {
                searchOrder(phone);
                showNotification('Status diperbarui', 'success');
            }
        });
    }

    // Event Listener untuk tombol Cari Pesanan Lain
    const trackAnother = document.getElementById('track-another');
    if (trackAnother) {
        trackAnother.addEventListener('click', function() {
            document.getElementById('tracking-result').style.display = 'none';
            document.getElementById('tracking-phone').value = '';
            document.getElementById('tracking-phone').focus();
        });
    }

    // Event Listener untuk close modal
    document.querySelectorAll('.close-modal').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('tracking-modal').style.display = 'none';
        });
    });

    // Close modal ketika klik di luar
    window.addEventListener('click', function(e) {
        if (e.target === document.getElementById('tracking-modal')) {
            document.getElementById('tracking-modal').style.display = 'none';
        }
    });
});

let currentTrackingPhone = '';

// Fungsi untuk mencari pesanan
async function searchOrder(phone) {
    const trackBtn = document.getElementById('track-order');
    if (!trackBtn) return;
    
    const originalText = trackBtn.innerHTML;
    
    try {
        console.log('🔍 Mencari pesanan dengan nomor:', phone);
        
        // Show loading
        trackBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
        trackBtn.disabled = true;
        
        currentTrackingPhone = phone;
        
        // Bersihkan nomor telepon
        const cleanPhone = phone.replace(/[^0-9]/g, '');
        
        // Panggil API
        const apiUrl = `api/get_orders.php?phone=${encodeURIComponent(cleanPhone)}`;
        console.log('🌐 API URL:', apiUrl);
        
        const response = await fetch(apiUrl);
        console.log('📡 Response status:', response.status);
        
        const result = await response.json();
        console.log('📦 Response data:', result);
        
        if (response.ok && result.success) {
            if (result.data && result.data.length > 0) {
                console.log('✅ Pesanan ditemukan:', result.data.length, 'pesanan');
                const latestOrder = result.data[0];
                displayOrderStatus(latestOrder);
                document.getElementById('tracking-result').style.display = 'block';
                showNotification('Pesanan ditemukan!', 'success');
            } else {
                console.log('❌ Tidak ada pesanan ditemukan');
                document.getElementById('tracking-result').style.display = 'none';
                showNotification('Tidak ada pesanan ditemukan untuk nomor ini', 'warning');
            }
        } else {
            throw new Error(result.message || 'Gagal mengambil data dari server');
        }
        
    } catch (error) {
        console.error('❌ Error searching order:', error);
        showNotification('Gagal mencari pesanan: ' + error.message, 'error');
    } finally {
        trackBtn.innerHTML = originalText;
        trackBtn.disabled = false;
    }
}

// Fungsi untuk menampilkan status pesanan
function displayOrderStatus(order) {
    console.log('📊 Menampilkan order:', order);
    
    // Update order details
    document.getElementById('detail-order-id').textContent = order.id || '-';
    document.getElementById('detail-customer-name').textContent = order.customer_name || '-';
    document.getElementById('detail-customer-phone').textContent = order.customer_phone || '-';
    document.getElementById('detail-customer-address').textContent = order.customer_address || 'Ambil di tempat';
    document.getElementById('detail-total-amount').textContent = 'Rp ' + (order.total_amount || 0).toLocaleString();
    
    // Format timestamp
    const orderTime = order.created_at;
    document.getElementById('order-time').textContent = formatDate(orderTime);
    
    // Update order items
    const itemsContainer = document.getElementById('detail-order-items');
    itemsContainer.innerHTML = '';
    
    if (order.items && order.items.length > 0) {
        order.items.forEach(item => {
            const itemElement = document.createElement('div');
            itemElement.className = 'order-item-detail';
            
            const itemName = item.name || 'Item';
            const itemQuantity = item.quantity || 1;
            const itemPrice = item.price || 0;
            
            itemElement.innerHTML = `
                <span>${itemName} (${itemQuantity}x)</span>
                <span>Rp ${(itemPrice * itemQuantity).toLocaleString()}</span>
            `;
            itemsContainer.appendChild(itemElement);
        });
    } else {
        itemsContainer.innerHTML = '<div class="no-items">Tidak ada detail item</div>';
    }
    
    // Update status timeline
    const status = order.status || 'pending';
    console.log('🔄 Update status timeline:', status);
    updateStatusTimeline(status);
}

// Fungsi untuk update timeline status
function updateStatusTimeline(status) {
    console.log('📈 Update timeline dengan status:', status);
    
    // Reset all steps
    const steps = document.querySelectorAll('.status-step');
    steps.forEach(step => {
        step.classList.remove('active', 'completed');
    });
    
    // Define status progression
    const statusProgression = ['pending', 'processing', 'ready', 'on_delivery', 'completed'];
    const currentStatusIndex = statusProgression.indexOf(status);
    
    console.log('📊 Status index:', currentStatusIndex);
    
    if (currentStatusIndex === -1) {
        console.warn('❌ Status tidak dikenali:', status);
        return;
    }
    
    // Activate steps based on current status
    steps.forEach((step, index) => {
        if (index <= currentStatusIndex) {
            if (index === currentStatusIndex) {
                step.classList.add('active');
            } else {
                step.classList.add('completed');
            }
        }
    });
    
    // Update step times
    updateStepTimes();
}

// Fungsi untuk update waktu step
function updateStepTimes() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });
    
    // Update active steps dengan waktu saat ini
    document.querySelectorAll('.status-step.active').forEach(step => {
        const timeSpan = step.querySelector('.step-info span');
        if (timeSpan && timeSpan.textContent === '-') {
            timeSpan.textContent = timeString;
        }
    });
}

// Format date untuk display
function formatDate(timestamp) {
    if (!timestamp) return '-';
    
    try {
        const date = new Date(timestamp);
        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        console.error('Error formatting date:', e);
        return '-';
    }
}

// Fungsi untuk show notification
function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    if (notification) {
        notification.textContent = message;
        notification.className = `notification ${type} show`;
        
        setTimeout(() => {
            notification.classList.remove('show');
        }, 3000);
    }
}

// ===== DEBUG FUNCTION =====
function debugTracking() {
    console.log('=== 🐛 DEBUG TRACKING SYSTEM ===');
    
    const elements = {
        'track-order-btn': document.getElementById('track-order-btn'),
        'tracking-modal': document.getElementById('tracking-modal'),
        'track-order': document.getElementById('track-order'),
        'tracking-phone': document.getElementById('tracking-phone'),
        'tracking-result': document.getElementById('tracking-result')
    };
    
    Object.entries(elements).forEach(([name, element]) => {
        console.log(`${name}:`, element ? '✅ ADA' : '❌ TIDAK ADA');
    });
}