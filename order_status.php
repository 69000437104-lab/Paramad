<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ติดตามสถานะออเดอร์ - ก๋วยจั๊บญวณ</title>
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
    </style>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Navbar -->
    <header class="bg-white border-b sticky top-0 z-30 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-2 text-gray-600 hover:text-orange-500 font-medium text-sm">
                <i class="fa-solid fa-arrow-left"></i> กลับไปเลือกเมนู
            </a>
            <span class="text-sm font-bold text-orange-600 flex items-center gap-1.5">
                <i class="fa-solid fa-receipt"></i> ติดตามออเดอร์
            </span>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 py-8 flex-1 w-full">

        <!-- Loading State -->
        <div id="loadingState" class="bg-white rounded-2xl p-8 text-center shadow-sm">
            <i class="fa-solid fa-circle-notch fa-spin text-3xl text-orange-500 mb-3"></i>
            <p class="text-gray-500 text-sm">กำลังโหลดข้อมูลออเดอร์...</p>
        </div>

        <!-- Order Content Container -->
        <div id="orderContent" class="hidden space-y-6">

            <!-- Status Banner & Stepper -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
                <div class="inline-block bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-semibold mb-3">
                    หมายเลขออเดอร์: <span id="ordNumberDisplay" class="font-bold text-orange-800"></span>
                </div>
                
                <h2 id="statusTitle" class="text-2xl font-bold text-gray-900 mb-1">กำลังโหลด...</h2>
                <p id="statusDesc" class="text-sm text-gray-500 mb-6">ระบบจะอัปเดตสถานะให้ท่านโดยอัตโนมัติ</p>

                <!-- Status Progress Stepper -->
                <div class="relative flex items-center justify-between max-w-md mx-auto px-4">
                    <!-- Progress Line -->
                    <div class="absolute left-10 right-10 top-5 h-1 bg-gray-200 -z-0">
                        <div id="progressLine" class="h-full bg-orange-500 transition-all duration-500 w-0"></div>
                    </div>

                    <!-- Step 1: Pending -->
                    <div class="flex flex-col items-center z-10">
                        <div id="step1Circle" class="w-10 h-10 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold shadow-md transition">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <span class="text-xs font-semibold mt-2 text-gray-700">รอรับออเดอร์</span>
                    </div>

                    <!-- Step 2: Cooking -->
                    <div class="flex flex-col items-center z-10">
                        <div id="step2Circle" class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold transition">
                            <i class="fa-solid fa-fire-burner"></i>
                        </div>
                        <span class="text-xs font-semibold mt-2 text-gray-500" id="step2Label">กำลังทำ</span>
                    </div>

                    <!-- Step 3: Completed -->
                    <div class="flex flex-col items-center z-10">
                        <div id="step3Circle" class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold transition">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <span class="text-xs font-semibold mt-2 text-gray-500" id="step3Label">เสร็จแล้ว</span>
                    </div>
                </div>

                <div class="mt-6 text-xs text-gray-400 flex items-center justify-center gap-1">
                    <i class="fa-solid fa-arrows-rotate animate-spin"></i> อัปเดตสถานะอัตโนมัติทุก 5 วินาที
                </div>
            </div>

            <!-- Receipt & Items Details -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-base mb-4 pb-2 border-b flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-orange-500"></i> รายละเอียดการสั่งซื้อ
                </h3>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-4 text-xs bg-gray-50 p-4 rounded-xl mb-4">
                    <div>
                        <span class="text-gray-400 block">ชื่อลูกค้า</span>
                        <span id="custNameText" class="font-bold text-gray-800 text-sm"></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">เบอร์โทรศัพท์</span>
                        <span id="custPhoneText" class="font-semibold text-gray-800"></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">โต๊ะ / ที่อยู่</span>
                        <span id="custTableText" class="font-semibold text-gray-800"></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">ประเภทออเดอร์</span>
                        <span id="orderTypeText" class="font-semibold text-orange-600"></span>
                    </div>
                </div>

                <!-- Food Items Table -->
                <div class="divide-y divide-gray-100 mb-4">
                    <div id="itemsContainer" class="divide-y divide-gray-100">
                        <!-- Populated dynamically -->
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="pt-4 border-t flex justify-between items-center text-base font-bold">
                    <span class="text-gray-700">ยอดรวมทั้งสิ้น</span>
                    <span id="totalAmountText" class="text-2xl text-orange-600">฿0.00</span>
                </div>
            </div>

            <!-- Actions Buttons -->
            <div class="flex gap-3">
                <a href="index.php" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-center font-bold py-3 px-4 rounded-xl text-sm transition">
                    ➕ สั่งอาหารเพิ่ม
                </a>
                <button onclick="fetchOrderStatus()" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-xl text-sm transition shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rotate-right"></i> รีเฟรชสถานะ
                </button>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t py-4 text-center text-xs text-gray-400 mt-auto">
        © 2026 ก๋วยจั๊บญวณ — ขอบคุณที่ใช้บริการ
    </footer>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const orderNumber = urlParams.get('order_number');

        document.addEventListener('DOMContentLoaded', () => {
            if (!orderNumber) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ไม่พบหมายเลขออเดอร์',
                    text: 'กรุณากลับไปที่หน้าสั่งอาหาร'
                }).then(() => {
                    window.location.href = 'index.php';
                });
                return;
            }

            fetchOrderStatus();
            // Auto refresh every 5 seconds
            setInterval(fetchOrderStatus, 5000);
        });

        async function fetchOrderStatus() {
            try {
                const response = await fetch(`api/order_api.php?action=get_order_status&order_number=${orderNumber}`);
                const data = await response.json();

                if (data.status) {
                    renderOrderStatus(data.data.order, data.data.items);
                } else {
                    document.getElementById('loadingState').innerHTML = `
                        <i class="fa-solid fa-triangle-exclamation text-3xl text-red-500 mb-3"></i>
                        <p class="text-red-600 font-bold mb-2">${data.message}</p>
                        <a href="index.php" class="inline-block bg-orange-500 text-white text-xs px-4 py-2 rounded-lg font-medium">กลับหน้าหลัก</a>
                    `;
                }
            } catch (err) {
                console.error(err);
            }
        }

        function renderOrderStatus(order, items) {
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('orderContent').classList.remove('hidden');

            document.getElementById('ordNumberDisplay').innerText = order.order_number;
            document.getElementById('custNameText').innerText = order.customer_name;
            document.getElementById('custPhoneText').innerText = order.customer_phone;
            document.getElementById('custTableText').innerText = order.table_no || '-';

            const typeMap = {
                'dine_in': 'ทานที่ร้าน 🪑',
                'takeaway': 'สั่งกลับบ้าน 🛍️',
                'delivery': 'จัดส่งถึงที่ 🛵'
            };
            document.getElementById('orderTypeText').innerText = typeMap[order.order_type] || order.order_type;
            document.getElementById('totalAmountText').innerText = `฿${parseFloat(order.total_amount).toFixed(2)}`;

            // Render Items
            const itemsContainer = document.getElementById('itemsContainer');
            itemsContainer.innerHTML = items.map(item => `
                <div class="py-3 flex justify-between items-center text-sm">
                    <div>
                        <div class="font-bold text-gray-800">${item.item_name}</div>
                        <div class="text-xs text-gray-400">฿${parseFloat(item.price).toFixed(2)} x ${item.quantity}</div>
                    </div>
                    <div class="font-bold text-gray-700">฿${parseFloat(item.subtotal).toFixed(2)}</div>
                </div>
            `).join('');

            // Update Status Stepper
            const status = order.status;
            const statusTitle = document.getElementById('statusTitle');
            const statusDesc  = document.getElementById('statusDesc');
            const progressLine = document.getElementById('progressLine');

            const step1Circle = document.getElementById('step1Circle');
            const step2Circle = document.getElementById('step2Circle');
            const step3Circle = document.getElementById('step3Circle');

            const step2Label = document.getElementById('step2Label');
            const step3Label = document.getElementById('step3Label');

            if (status === 'รอรับออเดอร์') {
                statusTitle.innerText = '⏳ รมร้านค้าตอบรับออเดอร์';
                statusDesc.innerText = 'ส่งออเดอร์เข้าครัวเรียบร้อยแล้ว พนักงานกำลังตรวจสอบ...';
                progressLine.style.width = '0%';

                step1Circle.className = 'w-10 h-10 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold shadow-md animate-pulse';
                step2Circle.className = 'w-10 h-10 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center font-bold';
                step3Circle.className = 'w-10 h-10 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center font-bold';

            } else if (status === 'กำลังทำ') {
                statusTitle.innerText = '🍳 ห้องครัวกำลังปรุงอาหาร';
                statusDesc.innerText = 'เชฟกำลังปรุงอาหารแสนอร่อยของคุณ โปรดรอสักครู่';
                progressLine.style.width = '50%';

                step1Circle.className = 'w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold';
                step2Circle.className = 'w-10 h-10 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold shadow-md animate-bounce';
                step3Circle.className = 'w-10 h-10 rounded-full bg-gray-200 text-gray-400 flex items-center justify-center font-bold';

                step2Label.classList.add('text-orange-600', 'font-bold');

            } else if (status === 'เสร็จแล้ว') {
                statusTitle.innerText = '🎉 อาหารทำเสร็จเรียบร้อย!';
                statusDesc.innerText = 'อาหารพร้อมเสิร์ฟ / รับประทานแล้ว ขอให้มีความสุขกับมื้ออาหาร';
                progressLine.style.width = '100%';

                step1Circle.className = 'w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold';
                step2Circle.className = 'w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold';
                step3Circle.className = 'w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md';

                step2Label.classList.add('text-emerald-600', 'font-bold');
                step3Label.classList.add('text-emerald-600', 'font-bold');

            } else if (status === 'ยกเลิก') {
                statusTitle.innerText = '❌ ออเดอร์นี้ถูกยกเลิกแล้ว';
                statusDesc.innerText = 'ขออภัย ออเดอร์ถูกยกเลิก โปรดติดต่อพนักงานประจำร้าน';
                progressLine.style.width = '0%';
                step1Circle.className = 'w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center font-bold';
            }
        }
    </script>
</body>
</html>
