<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ก๋วยจั๊บญวณ - สั่งอาหารออนไลน์</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Google Font Prompt -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Prompt', sans-serif; background-color: #f8fafc; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Header / Navbar -->
    <header class="bg-white border-b sticky top-0 z-30 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold text-xl shadow-md">
                    <i class="fa-solid fa-utensils"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-900 leading-tight">ก๋วยจั๊บญวณ</h1>
                    <div id="tableBadgeContainer" class="text-xs text-orange-600 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-location-dot"></i> <span id="tableBadgeText">สำหรับทานที่ร้าน / สั่งกลับบ้าน</span>
                    </div>
                </div>
            </div>
            
            <button onclick="toggleCartDrawer()" class="relative bg-orange-100 hover:bg-orange-200 text-orange-700 p-2.5 rounded-full transition flex items-center justify-center">
                <i class="fa-solid fa-cart-shopping text-xl"></i>
                <span id="cartCountBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white scale-0 transition-transform duration-200">0</span>
            </button>
        </div>

        <!-- Category Horizontal Filter Tabs -->
        <div class="max-w-6xl mx-auto px-4 pb-3">
            <div id="categoryTabs" class="flex gap-2 overflow-x-auto hide-scrollbar py-1">
                <!-- Dynamically loaded categories -->
                <button onclick="filterCategory(0)" class="cat-btn active bg-orange-500 text-white px-4 py-1.5 rounded-full text-sm font-medium whitespace-nowrap shadow-sm">
                    ✨ ทั้งหมด
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Menu Grid -->
    <main class="max-w-6xl mx-auto px-4 py-6 flex-1 w-full">
        <!-- Hero Banner / QR Table Alert -->
        <div id="welcomeBanner" class="bg-gradient-to-r from-orange-500 to-amber-500 text-white p-4 rounded-2xl mb-6 shadow-sm flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold mb-1">ยินดีต้อนรับ! 👋</h2>
                <p class="text-sm text-orange-100">เลือกรายการอาหารที่คุณชื่นชอบ แล้วกดสั่งซื้อได้ทันที</p>
            </div>
            <div class="text-3xl opacity-80 pl-2">
                🍜
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" id="searchInput" onkeyup="searchMenu()" placeholder="ค้นหาเมนูอาหาร..." class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 shadow-sm text-sm">
            </div>
        </div>

        <!-- Menu Loading Spinner -->
        <div id="menuLoading" class="text-center py-16">
            <i class="fa-solid fa-circle-notch fa-spin text-3xl text-orange-500 mb-3"></i>
            <p class="text-gray-500 text-sm">กำลังโหลดรายการอาหาร...</p>
        </div>

        <!-- Menu Item Cards Container -->
        <div id="menuGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 hidden">
            <!-- Dynamically populated food cards -->
        </div>

        <!-- Empty Menu State -->
        <div id="emptyMenuState" class="text-center py-16 hidden">
            <i class="fa-solid fa-bowl-food text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-base">ไม่พบรายการอาหารที่ค้นหา</p>
        </div>
    </main>

    <!-- Floating Cart Button (Mobile view bottom right) -->
    <div class="fixed bottom-6 right-6 z-40 sm:hidden">
        <button onclick="toggleCartDrawer()" class="bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-full shadow-lg flex items-center justify-center gap-2 font-bold transition transform active:scale-95">
            <div class="relative">
                <i class="fa-solid fa-cart-shopping text-xl"></i>
                <span id="floatingCartBadge" class="absolute -top-3 -right-3 bg-red-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center border border-white">0</span>
            </div>
            <span id="floatingCartTotal" class="text-sm">฿0</span>
        </button>
    </div>

    <!-- Slide-over Shopping Cart Drawer & Checkout -->
    <div id="cartDrawerBackdrop" onclick="toggleCartDrawer()" class="fixed inset-0 bg-black/50 z-50 opacity-0 pointer-events-none transition-opacity duration-300"></div>
    
    <div id="cartDrawer" class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-white z-50 shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
        <!-- Cart Header -->
        <div class="p-4 border-b flex items-center justify-between bg-orange-500 text-white">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-basket-shopping text-lg"></i>
                <h2 class="text-lg font-bold">ตะกร้าสินค้าของคุณ</h2>
            </div>
            <button onclick="toggleCartDrawer()" class="text-white hover:text-orange-200 text-xl w-8 h-8 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Cart Items List -->
        <div id="cartItemList" class="flex-1 overflow-y-auto p-4 divide-y divide-gray-100">
            <!-- Dynamically populated cart items -->
        </div>

        <!-- Empty Cart State -->
        <div id="emptyCartView" class="flex-1 flex flex-col items-center justify-center p-6 text-center">
            <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center text-orange-400 text-3xl mb-3">
                <i class="fa-solid fa-cart-flatbed"></i>
            </div>
            <h3 class="font-bold text-gray-700 text-lg mb-1">ตะกร้าของคุณยังว่างอยู่</h3>
            <p class="text-gray-400 text-sm mb-4">เลือกเมนูอาหารแสนอร่อยเพิ่มลงในตะกร้าได้เลย</p>
            <button onclick="toggleCartDrawer()" class="bg-orange-500 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-orange-600 transition">
                เลือกซื้ออาหาร
            </button>
        </div>

        <!-- Cart Footer / Customer Info Form -->
        <div id="cartFooter" class="border-t bg-gray-50 p-4 hidden">
            <!-- Total summary -->
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600 font-medium">ยอดรวมทั้งหมด</span>
                <span id="cartTotalPrice" class="text-2xl font-bold text-orange-600">฿0.00</span>
            </div>

            <!-- Customer Details Form -->
            <form id="checkoutForm" onsubmit="submitOrder(event)" class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">👤 ชื่อของคุณ <span class="text-red-500">*</span></label>
                    <input type="text" id="custName" required placeholder="เช่น คุณสมชาย" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">📞 เบอร์โทรศัพท์ <span class="text-red-500">*</span></label>
                        <input type="tel" id="custPhone" required placeholder="0812345678" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">🪑 โต๊ะ / ที่อยู่</label>
                        <input type="text" id="custTable" placeholder="เช่น โต๊ะ 05" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">🍽️ ประเภทการสั่ง</label>
                    <select id="orderType" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:outline-none">
                        <option value="dine_in">ทานที่ร้าน (Dine-in)</option>
                        <option value="takeaway">สั่งกลับบ้าน (Takeaway)</option>
                        <option value="delivery">จัดส่งถึงที่ (Delivery)</option>
                    </select>
                </div>

                <button type="submit" id="btnSubmitOrder" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-xl shadow-md transition flex items-center justify-center gap-2 text-base mt-2">
                    <span>ยืนยันออเดอร์ & สั่งซื้อ</span>
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t mt-auto py-4 text-center text-xs text-gray-400">
        © 2026 ก๋วยจั๊บญวณ — ระบบสั่งอาหารออนไลน์
    </footer>

    <script>
        let allMenuItems = [];
        let allCategories = [];
        let activeCategoryId = 0;
        let cart = []; // Array of { id, name, price, image, quantity }

        // Initialize Page
        document.addEventListener('DOMContentLoaded', () => {
            detectTableFromURL();
            loadMenuData();
            loadCartFromStorage();
        });

        // Detect Table from QR Code URL Parameter ?table=5
        function detectTableFromURL() {
            const urlParams = new URLSearchParams(window.location.search);
            const tableParam = urlParams.get('table');
            if (tableParam) {
                document.getElementById('custTable').value = 'โต๊ะ ' + tableParam;
                document.getElementById('tableBadgeText').innerText = `สั่งอาหารที่ โต๊ะ ${tableParam}`;
                document.getElementById('welcomeBanner').innerHTML = `
                    <div>
                        <h2 class="text-xl font-bold mb-1">ยินดีต้อนรับ โต๊ะ ${tableParam}! 🪑</h2>
                        <p class="text-sm text-orange-100">เลือกรายการอาหารแล้วกดสั่งได้เลย ออเดอร์จะส่งตรงเข้าครัวทันที</p>
                    </div>
                    <div class="text-3xl opacity-80 pl-2">🍜</div>
                `;
            }
        }

        // Fetch Categories & Menu Items from API
        async function loadMenuData() {
            try {
                const res = await fetch('api/order_api.php?action=get_menu');
                const data = await res.json();
                if (data.status) {
                    allCategories = data.data.categories;
                    allMenuItems  = data.data.menu_items;
                    
                    renderCategories();
                    renderMenu();
                } else {
                    Swal.fire('ข้อผิดพลาด', data.message, 'error');
                }
            } catch (err) {
                console.error(err);
            } finally {
                document.getElementById('menuLoading').classList.add('hidden');
                document.getElementById('menuGrid').classList.remove('hidden');
            }
        }

        // Render Category Tabs
        function renderCategories() {
            const container = document.getElementById('categoryTabs');
            let html = `
                <button onclick="filterCategory(0)" class="cat-btn ${activeCategoryId === 0 ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'} px-4 py-1.5 rounded-full text-sm font-medium whitespace-nowrap transition">
                    ✨ ทั้งหมด
                </button>
            `;
            allCategories.forEach(c => {
                const isActive = activeCategoryId === c.id;
                html += `
                    <button onclick="filterCategory(${c.id})" class="cat-btn ${isActive ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'} px-4 py-1.5 rounded-full text-sm font-medium whitespace-nowrap transition">
                        ${c.name}
                    </button>
                `;
            });
            container.innerHTML = html;
        }

        function filterCategory(catId) {
            activeCategoryId = catId;
            renderCategories();
            renderMenu();
        }

        // Search Filter
        function searchMenu() {
            renderMenu();
        }

        // Render Menu Cards
        function renderMenu() {
            const searchKeyword = document.getElementById('searchInput').value.toLowerCase().trim();
            const grid = document.getElementById('menuGrid');
            const emptyState = document.getElementById('emptyMenuState');

            let filtered = allMenuItems.filter(item => {
                const matchCategory = activeCategoryId === 0 || item.category_id == activeCategoryId;
                const matchSearch   = item.name.toLowerCase().includes(searchKeyword) || (item.description && item.description.toLowerCase().includes(searchKeyword));
                return matchCategory && matchSearch;
            });

            if (filtered.length === 0) {
                grid.classList.add('hidden');
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            grid.classList.remove('hidden');

            grid.innerHTML = filtered.map(item => `
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition border border-gray-100 flex flex-col">
                    <div class="relative h-44 overflow-hidden bg-gray-100">
                        <img src="${item.image || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80'}" alt="${item.name}" class="w-full h-full object-cover transform hover:scale-105 transition duration-300">
                        <span class="absolute top-3 left-3 bg-black/60 backdrop-blur-md text-white text-xs px-2.5 py-1 rounded-full font-medium">
                            ${item.category_name}
                        </span>
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-base mb-1">${item.name}</h3>
                            <p class="text-gray-500 text-xs line-clamp-2 mb-3">${item.description || ''}</p>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                            <span class="text-lg font-bold text-orange-600">฿${parseFloat(item.price).toFixed(2)}</span>
                            <button onclick="addToCart(${item.id})" class="bg-orange-500 hover:bg-orange-600 text-white px-3.5 py-1.5 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition active:scale-95 shadow-sm">
                                <i class="fa-solid fa-plus"></i> เพิ่มลงตะกร้า
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Cart Management
        function addToCart(itemId) {
            const item = allMenuItems.find(m => m.id == itemId);
            if (!item) return;

            const existingIndex = cart.findIndex(c => c.id == itemId);
            if (existingIndex > -1) {
                cart[existingIndex].quantity += 1;
            } else {
                cart.push({
                    id: item.id,
                    name: item.name,
                    price: parseFloat(item.price),
                    image: item.image,
                    quantity: 1
                });
            }

            saveCartToStorage();
            updateCartUI();

            // Toast animation
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: `เพิ่ม ${item.name} ลงตะกร้าแล้ว`
            });
        }

        function changeQuantity(itemId, delta) {
            const index = cart.findIndex(c => c.id == itemId);
            if (index > -1) {
                cart[index].quantity += delta;
                if (cart[index].quantity <= 0) {
                    cart.splice(index, 1);
                }
                saveCartToStorage();
                updateCartUI();
            }
        }

        function removeFromCart(itemId) {
            cart = cart.filter(c => c.id != itemId);
            saveCartToStorage();
            updateCartUI();
        }

        function updateCartUI() {
            const totalCount = cart.reduce((sum, i) => sum + i.quantity, 0);
            const totalPrice = cart.reduce((sum, i) => sum + (i.price * i.quantity), 0);

            // Badges
            const cartBadge = document.getElementById('cartCountBadge');
            const floatBadge = document.getElementById('floatingCartBadge');
            
            cartBadge.innerText = totalCount;
            floatBadge.innerText = totalCount;

            if (totalCount > 0) {
                cartBadge.classList.remove('scale-0');
            } else {
                cartBadge.classList.add('scale-0');
            }

            document.getElementById('floatingCartTotal').innerText = `฿${totalPrice.toFixed(2)}`;
            document.getElementById('cartTotalPrice').innerText = `฿${totalPrice.toFixed(2)}`;

            // Drawer Contents
            const list = document.getElementById('cartItemList');
            const emptyView = document.getElementById('emptyCartView');
            const footerView = document.getElementById('cartFooter');

            if (cart.length === 0) {
                list.innerHTML = '';
                emptyView.classList.remove('hidden');
                footerView.classList.add('hidden');
            } else {
                emptyView.classList.add('hidden');
                footerView.classList.remove('hidden');

                list.innerHTML = cart.map(item => `
                    <div class="py-3 flex items-center gap-3">
                        <img src="${item.image || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80'}" class="w-14 h-14 rounded-xl object-cover border">
                        <div class="flex-1">
                            <h4 class="font-bold text-sm text-gray-800">${item.name}</h4>
                            <div class="text-xs text-orange-600 font-semibold mt-0.5">฿${item.price.toFixed(2)}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex items-center border rounded-lg overflow-hidden bg-gray-50">
                                <button onclick="changeQuantity(${item.id}, -1)" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-200 text-xs font-bold">-</button>
                                <span class="w-7 text-center text-xs font-bold text-gray-800">${item.quantity}</span>
                                <button onclick="changeQuantity(${item.id}, 1)" class="w-7 h-7 flex items-center justify-center text-gray-600 hover:bg-gray-200 text-xs font-bold">+</button>
                            </div>
                            <button onclick="removeFromCart(${item.id})" class="text-red-400 hover:text-red-600 text-sm p-1">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </div>
                    </div>
                `).join('');
            }
        }

        function toggleCartDrawer() {
            const drawer = document.getElementById('cartDrawer');
            const backdrop = document.getElementById('cartDrawerBackdrop');
            const isHidden = drawer.classList.contains('translate-x-full');

            if (isHidden) {
                drawer.classList.remove('translate-x-full');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
            } else {
                drawer.classList.add('translate-x-full');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
            }
        }

        function saveCartToStorage() {
            localStorage.setItem('orderfood_cart', JSON.stringify(cart));
        }

        function loadCartFromStorage() {
            const saved = localStorage.getItem('orderfood_cart');
            if (saved) {
                try { cart = JSON.parse(saved); } catch(e) { cart = []; }
            }
            updateCartUI();
        }

        // Submit Order to API
        async function submitOrder(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitOrder');
            
            const customer_name  = document.getElementById('custName').value.trim();
            const customer_phone = document.getElementById('custPhone').value.trim();
            const table_no       = document.getElementById('custTable').value.trim();
            const order_type     = document.getElementById('orderType').value;

            if (cart.length === 0) {
                Swal.fire('คำเตือน', 'กรุณาเลือกรายการอาหารก่อนสั่งซื้อ', 'warning');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> กำลังส่งออเดอร์...';

            try {
                const response = await fetch('api/order_api.php?action=create_order', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        customer_name,
                        customer_phone,
                        table_no,
                        order_type,
                        cart
                    })
                });

                const resData = await response.json();
                if (resData.status) {
                    // Clear cart
                    cart = [];
                    saveCartToStorage();
                    updateCartUI();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'สั่งซื้ออาหารเรียบร้อยแล้ว!',
                        text: `หมายเลขออเดอร์ของคุณคือ ${resData.data.order_number}`,
                        confirmButtonText: 'ติดตามสถานะออเดอร์ 🚀',
                        confirmButtonColor: '#f97316'
                    }).then(() => {
                        window.location.href = `order_status.php?order_number=${resData.data.order_number}`;
                    });
                } else {
                    Swal.fire('ข้อผิดพลาด', resData.message, 'error');
                }
            } catch (err) {
                console.error(err);
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<span>ยืนยันออเดอร์ & สั่งซื้อ</span> <i class="fa-solid fa-paper-plane"></i>';
            }
        }
    </script>
</body>
</html>
