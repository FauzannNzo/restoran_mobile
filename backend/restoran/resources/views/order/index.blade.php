<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Menu Restoran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .menu-img-container {
            aspect-ratio: 1/1;
            overflow: hidden;
        }

        .menu-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .menu-item {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-50 pb-32 text-gray-800 antialiased">

    <div class="fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-sm z-30 shadow-sm border-b border-gray-200">
        <div class="px-4 py-3 flex justify-between items-center">
            <div>
                <h1 class="font-extrabold text-xl text-gray-900 tracking-tight leading-none">Rasa<span class="text-blue-600">Nyaman</span></h1>
            </div>
            <div onclick="openCartModal()" class="relative p-2 text-gray-400 hover:text-blue-600 transition cursor-pointer">
                <i class="ph-bold ph-shopping-cart text-xl"></i>
                <span id="headerCartCount" class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-[9px] font-bold flex items-center justify-center rounded-full hidden">0</span>
            </div>
        </div>

        <div class="flex overflow-x-auto whitespace-nowrap px-4 pb-0 gap-6 no-scrollbar bg-white">
            <button onclick="filterCategory('all', this)" class="filter-btn active pb-3 text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition-colors">
                Semua
            </button>
            @foreach($kategoris as $kategori)
            <button onclick="filterCategory('cat-{{ $kategori->id }}', this)" class="filter-btn pb-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-blue-600 transition-colors">
                {{ $kategori->nama_kategori }}
            </button>
            @endforeach
        </div>
    </div>

    <div class="container mx-auto px-4 mt-32 max-w-2xl min-h-screen">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="menuGrid">
            @foreach($kategoris as $kategori)
            @foreach($kategori->menu as $menu)
            <div class="menu-item cat-{{ $kategori->id }} bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer flex flex-col h-full active:scale-95 transition-transform duration-200"
                onclick="openProductModal({{ json_encode($menu) }})">

                <div class="menu-img-container relative bg-gray-100">
                    <img src="{{ $menu->foto ? asset('storage/'.$menu->foto) : 'https://via.placeholder.com/300?text=Menu' }}"
                        class="menu-img">
                    @if($menu->stok_porsi < 5 && $menu->stok_porsi > 0)
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded shadow-sm">Sisa {{ $menu->stok_porsi }}</span>
                        @endif
                </div>

                <div class="p-3 flex flex-col flex-grow justify-between">
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm leading-tight line-clamp-1 mb-1">{{ $menu->nama_menu }}</h4>
                        <p class="text-[10px] text-gray-500 line-clamp-2 h-8 leading-relaxed">{{ $menu->deskripsi }}</p>
                    </div>
                    <div class="mt-2">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-bold text-blue-600 text-sm">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            <span class="text-gray-300 text-sm">Stok: {{ $menu->stok_porsi }}</span>
                        </div>
                        <button class="w-full bg-blue-50 text-blue-600 border border-blue-100 text-xs font-bold py-2 rounded-lg hover:bg-blue-600 hover:text-white transition flex items-center justify-center gap-1">
                            Tambah +
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
            @endforeach
        </div>
        <div id="emptyMessage" class="hidden text-center py-20">
            <p class="text-gray-400 text-sm">Kategori ini belum ada menunya.</p>
        </div>
    </div>

    <div id="floatingCart" class="fixed bottom-6 left-0 right-0 z-40 hidden justify-center px-4 pointer-events-none">
        <div onclick="openCartModal()" class="bg-blue-600 text-white w-full max-w-[22rem] rounded-full px-5 py-3 flex justify-between items-center shadow-lg shadow-blue-600/40 pointer-events-auto cursor-pointer transform transition-all hover:scale-[1.02] active:scale-95">
            <div class="flex flex-col">
                <div class="flex items-center gap-1.5 text-blue-100 text-[11px] font-semibold mb-1 tracking-wide">
                    <i class="ph-bold ph-shopping-cart text-sm"></i>
                    <span><span id="totalItems">0</span> Item</span>
                </div>
                <div class="font-extrabold text-lg leading-none text-white">
                    Rp <span id="totalPriceFloat">0</span>
                </div>
            </div>
            <div class="flex items-center gap-1 font-bold text-sm tracking-wide text-white">
                <span>Keranjang</span>
                <i class="ph-bold ph-caret-right text-lg"></i>
            </div>
        </div>
    </div>

    <div id="productModal" class="modal fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="absolute inset-0" onclick="closeProductModal()"></div>
        <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl relative z-10 overflow-hidden transform transition-all scale-100">
            <div class="p-5 border-b border-gray-100 flex justify-between items-start">
                <div>
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-900 leading-tight">Nama Menu</h3>
                    <p id="modalPrice" class="text-lg font-bold text-blue-600 mt-1">Rp 0</p>
                </div>
                <button onclick="closeProductModal()" class="p-1 text-gray-400 hover:text-gray-600">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>
            <div class="p-5">
                <p id="modalDesc" class="text-gray-500 text-sm leading-relaxed mb-4">Deskripsi menu...</p>
                <div class="mb-5">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 block">Catatan</label>
                    <input type="text" id="modalNote" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" placeholder="Contoh: Jangan pedas...">
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center bg-gray-100 rounded-xl p-1">
                        <button onclick="adjustQty(-1)" class="w-10 h-10 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-600 font-bold text-lg">-</button>
                        <span id="modalQty" class="font-bold w-10 text-center text-gray-800">1</span>
                        <button onclick="adjustQty(1)" class="w-10 h-10 flex items-center justify-center bg-blue-600 rounded-lg shadow-sm text-white font-bold text-lg">+</button>
                    </div>
                    <button onclick="addToCart()" class="flex-1 bg-gray-900 text-white py-3 rounded-xl font-bold shadow-lg text-sm flex justify-between px-4 items-center">
                        <span>Tambah</span>
                        <span>Rp <span id="modalSubtotal">0</span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="cartModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeCartModal()"></div>

        <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">

            <div class="bg-gray-50 w-full max-w-md rounded-2xl shadow-2xl relative flex flex-col max-h-[90vh] pointer-events-auto overflow-hidden">

                <div class="bg-white p-4 border-b border-gray-100 flex justify-between items-center shrink-0">
                    <h2 class="text-lg font-bold text-gray-900">Konfirmasi Pesanan</h2>
                    <button onclick="closeCartModal()" class="p-2 text-gray-400 hover:bg-gray-100 rounded-full transition">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-4">

                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-xs font-bold text-gray-800 mb-3">Tipe Pesanan</h3>
                        <div class="bg-gray-100 p-1 rounded-lg flex font-bold text-sm">
                            <button onclick="setMetode('dine in')" id="btnDineIn" class="flex-1 py-2 rounded-md bg-white text-blue-600 shadow transition-all duration-200">Makan di Sini</button>
                            <button onclick="setMetode('take away')" id="btnTakeAway" class="flex-1 py-2 rounded-md text-gray-500 hover:text-gray-700 transition-all duration-200">Bawa Pulang</button>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 space-y-4">
                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-500 mb-1">
                                <i class="ph-fill ph-user text-gray-400"></i> Nama Anda
                            </label>
                            <input type="text" id="customerName" class="w-full text-sm font-semibold text-gray-900 border-b border-gray-300 py-1.5 focus:outline-none focus:border-blue-600 transition bg-transparent" placeholder="Masukkan nama pemesan...">
                        </div>

                        <div id="tableSelectionContainer">
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-500 mb-2">
                                <i class="ph-fill ph-armchair text-gray-400"></i> Pilih Meja
                            </label>
                            <div class="grid grid-cols-5 gap-2">
                                @foreach($mejas as $meja)
                                @php
                                $isBooked = $meja->status == 'booking';
                                $isSelected = $noMeja == $meja->id;
                                if($isBooked) $class = 'bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed';
                                elseif($isSelected) $class = 'bg-blue-600 border-blue-600 text-white ring-2 ring-blue-200 shadow-md';
                                else $class = 'bg-white border-gray-300 text-gray-700 hover:border-blue-500 cursor-pointer';
                                @endphp
                                <div @if(!$isBooked) onclick="selectTable({{ $meja->id }}, '{{ $meja->no_meja }}')" @endif
                                    id="meja-btn-{{ $meja->id }}"
                                    class="meja-item border rounded-lg p-1 h-10 flex items-center justify-center transition-all duration-200 {{ $class }}">
                                    <span class="text-xs font-bold">{{ $meja->no_meja }}</span>
                                </div>
                                @endforeach
                            </div>
                            <input type="hidden" id="selectedTableId" value="{{ $noMeja }}">
                        </div>

                        <div>
                            <label class="flex items-center gap-2 text-xs font-bold text-gray-500 mb-2">
                                <i class="ph-fill ph-wallet text-gray-400"></i> Metode Pembayaran
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="border border-gray-200 rounded-lg p-3 flex items-center gap-2 cursor-pointer bg-white relative overflow-hidden has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="payment" value="cash" class="peer hidden" checked> <span class="text-xs font-bold text-gray-800">Tunai (Kasir)</span>
                                    <div class="absolute top-2 right-2 w-2 h-2 bg-blue-600 rounded-full hidden peer-checked:block"></div>
                                </label>
                                <label class="border border-gray-200 rounded-lg p-3 flex items-center gap-2 cursor-pointer bg-white relative overflow-hidden has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="payment" value="qris" class="peer hidden">
                                    <span class="text-xs font-bold text-gray-800">QRIS</span>
                                    <div class="absolute top-2 right-2 w-2 h-2 bg-blue-600 rounded-full hidden peer-checked:block"></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-xs font-bold text-gray-800 mb-3">Detail Pesanan:</h3>
                        <div id="cartItemsList" class="space-y-3">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-4 border-t border-gray-100 shrink-0">
                    <div class="flex justify-between items-end mb-4">
                        <span class="text-gray-600 font-bold text-sm">Total Bayar:</span>
                        <span class="font-extrabold text-2xl text-blue-600 leading-none">Rp <span id="cartTotal">0</span></span>
                    </div>

                    <form action="{{ route('order.store') }}" method="POST" id="finalOrderForm">
                        @csrf
                        <input type="hidden" name="nama_konsumen" id="formNama">
                        <input type="hidden" name="meja_id" id="formMeja"> <input type="hidden" name="metode_pembayaran" id="formPayment">

                        <input type="hidden" name="metode_pesanan" id="formMetodePesanan">
                        <input type="hidden" name="total_bayar" id="formTotalBayar">
                        <div id="formItemsContainer"></div>

                        <button type="button" onclick="submitOrder()" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 transition transform active:scale-95 flex justify-center items-center gap-2">
                            Kirim Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let cart = [];
        let currentMenu = {};
        let currentQty = 1;
        let metodeOrder = 'dine in';
        let selectedTable = "{{ $noMeja }}";
        const formatRupiah = (num) => new Intl.NumberFormat('id-ID').format(num);

        // Fungsi Buka Modal Produk
        function openProductModal(menu) {
            currentMenu = menu;
            currentQty = 1;
            document.getElementById('modalTitle').innerText = menu.nama_menu;
            document.getElementById('modalDesc').innerText = menu.deskripsi || '';
            document.getElementById('modalPrice').innerText = 'Rp ' + formatRupiah(menu.harga);
            document.getElementById('modalNote').value = '';
            updateModalCalc();
            document.getElementById('productModal').classList.remove('hidden');
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        function adjustQty(amount) {
            if (currentQty + amount >= 1 && currentQty + amount <= (currentMenu.stok_porsi || 99)) {
                currentQty += amount;
                updateModalCalc();
            }
        }

        function updateModalCalc() {
            document.getElementById('modalQty').innerText = currentQty;
            document.getElementById('modalSubtotal').innerText = formatRupiah(currentQty * currentMenu.harga);
        }

        // Tambah ke Keranjang
        function addToCart() {
            const note = document.getElementById('modalNote').value || '-';
            const existingItem = cart.find(item => item.id === currentMenu.id && item.catatan === note);

            if (existingItem) {
                existingItem.qty += currentQty;
            } else {
                cart.push({
                    id: currentMenu.id,
                    name: currentMenu.nama_menu,
                    price: currentMenu.harga,
                    qty: currentQty,
                    catatan: note,
                    stock: currentMenu.stok_porsi
                });
            }
            closeProductModal();
            updateFloatingCart();
        }

        function updateFloatingCart() {
            const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);
            const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

            const floatBar = document.getElementById('floatingCart');
            const headerCount = document.getElementById('headerCartCount');

            if (totalQty > 0) {
                document.getElementById('totalItems').innerText = totalQty;
                document.getElementById('totalPriceFloat').innerText = formatRupiah(totalPrice);
                floatBar.classList.remove('hidden');
                floatBar.classList.add('flex');

                if (headerCount) {
                    headerCount.innerText = totalQty;
                    headerCount.classList.remove('hidden');
                }
            } else {
                floatBar.classList.add('hidden');
                floatBar.classList.remove('flex');
                if (headerCount) headerCount.classList.add('hidden');
            }
        }

        function openCartModal() {
            renderCartItems();
            document.getElementById('cartModal').classList.remove('hidden');
        }

        function closeCartModal() {
            document.getElementById('cartModal').classList.add('hidden');
        }

        function renderCartItems() {
            const container = document.getElementById('cartItemsList');
            container.innerHTML = '';
            let grandTotal = 0;

            cart.forEach((item, index) => {
                const subtotal = item.price * item.qty;
                grandTotal += subtotal;
                const html = `
                <div class="flex justify-between items-start border-b border-gray-50 pb-2 last:border-0">
                    <div class="flex-1 pr-2">
                        <h4 class="font-bold text-xs text-gray-900 leading-tight">${item.name}</h4>
                        <p class="text-[10px] text-gray-500 italic line-clamp-1">${item.catatan !== '-' ? '"'+item.catatan+'"' : ''}</p>
                        <p class="text-[10px] text-blue-600 font-bold mt-0.5">Rp ${formatRupiah(item.price)}</p>
                    </div>
                    <div class="flex items-center bg-gray-50 rounded p-0.5 border border-gray-100">
                        <button onclick="updateCartQty(${index}, -1)" class="w-5 h-5 flex items-center justify-center bg-white rounded shadow-sm text-gray-500 font-bold text-xs hover:bg-gray-100">-</button>
                        <span class="text-xs font-bold w-5 text-center">${item.qty}</span>
                        <button onclick="updateCartQty(${index}, 1)" class="w-5 h-5 flex items-center justify-center bg-blue-600 rounded shadow-sm text-white font-bold text-xs hover:bg-blue-700">+</button>
                    </div>
                </div>
            `;
                container.innerHTML += html;
            });
            document.getElementById('cartTotal').innerText = formatRupiah(grandTotal);
        }

        function updateCartQty(index, amount) {
            const item = cart[index];
            if (item.qty + amount <= 0) {
                cart.splice(index, 1);
            } else {
                item.qty += amount;
            }
            renderCartItems();
            updateFloatingCart();
            if (cart.length === 0) closeCartModal();
        }

        function selectTable(id, nomor) {
            selectedTable = id;
            document.getElementById('selectedTableId').value = id;
            document.querySelectorAll('.meja-item').forEach(el => {
                el.classList.remove('bg-blue-600', 'border-blue-600', 'text-white', 'ring-2', 'ring-blue-200');
                el.classList.add('bg-white', 'border-gray-200', 'text-gray-600');
            });
            const activeBtn = document.getElementById(`meja-btn-${id}`);
            if (activeBtn) {
                activeBtn.classList.add('bg-blue-600', 'border-blue-600', 'text-white', 'ring-2', 'ring-blue-200');
            }
        }

        function setMetode(type) {
            metodeOrder = type;
            const btnDine = document.getElementById('btnDineIn');
            const btnTake = document.getElementById('btnTakeAway');
            const tableContainer = document.getElementById('tableSelectionContainer');

            if (type === 'dine in') {
                btnDine.classList.add('bg-white', 'text-blue-600', 'shadow');
                btnTake.classList.remove('bg-white', 'text-blue-600', 'shadow');
                btnTake.classList.add('text-gray-500');
                tableContainer.style.display = 'block';
            } else {
                btnTake.classList.add('bg-white', 'text-blue-600', 'shadow');
                btnDine.classList.remove('bg-white', 'text-blue-600', 'shadow');
                btnDine.classList.add('text-gray-500');
                tableContainer.style.display = 'none';
            }
        }

        // --- FUNGSI KIRIM PESANAN  ---
        async function submitOrder() {
            const nama = document.getElementById('customerName').value;
            const mejaId = document.getElementById('selectedTableId').value;

            // Validasi
            if (!nama.trim()) {
                alert('Mohon isi nama Anda');
                return;
            }
            if (metodeOrder === 'dine in' && !mejaId) {
                alert('Pilih nomor meja');
                return;
            }
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }

            // Ambil Metode Pembayaran
            const paymentSelected = document.querySelector('input[name="payment"]:checked');
            const paymentMethod = paymentSelected ? paymentSelected.value : 'cash';

            // Hitung Total
            const grandTotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

            // Susun Payload JSON 
            const payload = {
                nama_konsumen: nama,
                meja_id: metodeOrder === 'dine in' ? mejaId : null,
                metode_pembayaran: paymentMethod,
                items: cart.map(item => ({
                    id: item.id,
                    jumlah: item.qty,
                    catatan: item.catatan || '-',
                    subtotal: item.price * item.qty
                }))
            };

            // Ambil Token CSRF
            const csrfToken = document.querySelector('input[name="_token"]').value;
            const btnSubmit = document.querySelector('button[onclick="submitOrder()"]');

            try {
                // Loading State
                btnSubmit.disabled = true;
                btnSubmit.innerText = 'Memproses...';

                const response = await fetch("{{ route('order.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    cart = [];
                    localStorage.removeItem('myCart');
                    // Redirect ke halaman sukses
                    window.location.href = result.redirect;
                } else {
                    alert('Gagal: ' + (result.message || 'Terjadi kesalahan sistem'));
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = 'KONFIRMASI PESANAN';
                }
            } catch (error) {
                console.error(error);
                alert('Koneksi ke server terputus.');
                btnSubmit.disabled = false;
                btnSubmit.innerText = 'KONFIRMASI PESANAN';
            }
        }

        function filterCategory(categoryClass, btnElement) {
            // Ubah styling tombol yang aktif
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('text-blue-600', 'border-blue-600', 'font-bold');
                btn.classList.add('text-gray-500', 'border-transparent', 'font-medium');
            });
            btnElement.classList.remove('text-gray-500', 'border-transparent', 'font-medium');
            btnElement.classList.add('text-blue-600', 'border-blue-600', 'font-bold');

            // Filter Menu
            const items = document.querySelectorAll('.menu-item');
            let visibleCount = 0;

            items.forEach(item => {
                if (categoryClass === 'all' || item.classList.contains(categoryClass)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Tampilkan pesan jika kosong
            const emptyMsg = document.getElementById('emptyMessage');
            if (visibleCount === 0) {
                emptyMsg.classList.remove('hidden');
            } else {
                emptyMsg.classList.add('hidden');
            }
        }
    </script>
</body>

</html>