@extends('layouts.app')

@section('title', 'Pesan ' . $paket->name_paket)

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Beranda</a></li>
                <li><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('paket') }}" class="hover:text-emerald-600">Paket</a></li>
                <li><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('detail-paket', $paket->id) }}"
                        class="hover:text-emerald-600">{{ $paket->name_paket }}</a></li>
                <li><i class="fas fa-chevron-right"></i></li>
                <li class="text-gray-800 font-medium">Pesan</li>
            </ol>
        </nav>

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Paket Info -->
            <div>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Paket</h2>

                    <div class="flex items-start space-x-4 mb-6">
                        @if ($paket->foto1)
                            <img src="{{ asset('storage/pakets/' . $paket->foto1) }}" alt="{{ $paket->name_paket }}"
                                class="w-24 h-24 object-cover rounded-lg">
                        @else
                            <div
                                class="w-24 h-24 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-emerald-400 text-2xl"></i>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $paket->name_paket }}</h3>
                            <div class="flex space-x-2 mt-2">
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $paket->jenis }}
                                </span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $paket->kategori }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mt-2">{{ Str::limit($paket->deskripsi, 100) }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga per pax:</span>
                            <span class="font-bold text-emerald-600">
                                Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Minimum pax:</span>
                            <span class="font-bold text-gray-800">{{ $paket->jumlah_pax }}</span>
                        </div>
                        <hr class="my-4">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total Bayar:</span>
                            <span class="text-emerald-600" id="totalBayar">
                                Rp {{ number_format($paket->harga_paket * $paket->jumlah_pax, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info Pelanggan -->
                <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pengiriman</h2>
                    <div class="space-y-2">
                        <p class="text-gray-700">
                            <strong>Nama:</strong> {{ $pelanggan->name_pelanggan }}
                        </p>
                        <p class="text-gray-700">
                            <strong>Alamat:</strong> {{ $pelanggan->alamat1 }}
                        </p>
                        <p class="text-gray-700">
                            <strong>Telepon:</strong> {{ $pelanggan->telepon }}
                        </p>
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pastikan alamat pengiriman sudah benar. Jika ingin mengubah, silakan edit profil terlebih
                            dahulu.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Form Pesanan -->
            <div>
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Form Pemesanan</h2>

                    <form action="{{ route('pesan.store') }}" method="POST" id="pesanForm">
                        @csrf

                        <input type="hidden" name="paket_id" value="{{ $paket->id }}">

                        <div class="space-y-6">
                            <!-- Jumlah Pax -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Pax <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="jumlah_pax" id="jumlahPax"
                                    value="{{ old('jumlah_pax', $paket->jumlah_pax) }}" min="{{ $paket->jumlah_pax }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('jumlah_pax') border-red-500 @enderror"
                                    required>
                                <p class="text-xs text-gray-500 mt-1">Minimum {{ $paket->jumlah_pax }} pax</p>
                                @error('jumlah_pax')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div id="error-jumlah" class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>

                            <!-- Tanggal Pesan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal Acara <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tgl_pesan" id="tglPesan" min="{{ $minDate }}"
                                    value="{{ old('tgl_pesan', $minDate) }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('tgl_pesan') border-red-500 @enderror"
                                    required>
                                <p class="text-xs text-gray-500 mt-1">Pilih tanggal untuk acara Anda (minimal besok)</p>
                                @error('tgl_pesan')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div id="error-tanggal" class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>

                            <!-- Metode Pembayaran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select name="id_jenis_bayar" id="metodeBayar"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('id_jenis_bayar') border-red-500 @enderror"
                                    required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    @foreach ($jenisPembayarans as $jenis)
                                        @if ($jenis->detailJenisPembayarans && $jenis->detailJenisPembayarans->count() > 0)
                                            <optgroup label="{{ $jenis->metode_pembayaran }}">
                                                @foreach ($jenis->detailJenisPembayarans as $detail)
                                                    <option value="{{ $jenis->id }}"
                                                        {{ old('id_jenis_bayar') == $jenis->id ? 'selected' : '' }}>
                                                        {{ $jenis->metode_pembayaran }} - {{ $detail->tempat_bayar }}
                                                        ({{ $detail->no_rek }})
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $jenis->id }}"
                                                {{ old('id_jenis_bayar') == $jenis->id ? 'selected' : '' }}>
                                                {{ $jenis->metode_pembayaran }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('id_jenis_bayar')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                <div id="error-metode" class="text-red-500 text-xs mt-1 hidden"></div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex justify-between pt-4">
                                <a href="{{ route('detail-paket', $paket->id) }}"
                                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                </a>
                                <button type="submit" id="submitBtn"
                                    class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg hover:from-emerald-600 hover:to-teal-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-shopping-cart mr-2"></i> Buat Pesanan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Informasi Pembayaran -->
                <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Cara Pembayaran</h2>
                    <ol class="space-y-3 text-gray-700">
                        <li class="flex items-start">
                            <span
                                class="bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-3">1</span>
                            <span>Pilih metode pembayaran di atas</span>
                        </li>
                        <li class="flex items-start">
                            <span
                                class="bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-3">2</span>
                            <span>Transfer sesuai total yang tertera</span>
                        </li>
                        <li class="flex items-start">
                            <span
                                class="bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-3">3</span>
                            <span>Admin akan mengkonfirmasi pembayaran Anda</span>
                        </li>
                        <li class="flex items-start">
                            <span
                                class="bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center mr-3">4</span>
                            <span>Pesanan akan diproses oleh dapur kami</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateTotal() {
            const hargaPaket = {{ $paket->harga_paket }};
            const jumlahPax = document.getElementById('jumlahPax').value;
            const total = hargaPaket * jumlahPax;

            document.getElementById('totalBayar').textContent =
                'Rp ' + total.toLocaleString('id-ID');
        }

        function validateForm() {
            let isValid = true;

            document.querySelectorAll('[id^="error-"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });

            const jumlahPax = document.getElementById('jumlahPax').value;
            const minPax = {{ $paket->jumlah_pax }};
            if (jumlahPax < minPax) {
                document.getElementById('error-jumlah').textContent = `Jumlah pax minimal ${minPax}`;
                document.getElementById('error-jumlah').classList.remove('hidden');
                isValid = false;
            }

            const tglPesan = document.getElementById('tglPesan').value;
            const today = new Date().toISOString().split('T')[0];
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().split('T')[0];

            if (tglPesan < minDate) {
                document.getElementById('error-tanggal').textContent = 'Tanggal acara minimal besok';
                document.getElementById('error-tanggal').classList.remove('hidden');
                isValid = false;
            }

            const metodeBayar = document.getElementById('metodeBayar').value;
            if (!metodeBayar) {
                document.getElementById('error-metode').textContent = 'Pilih metode pembayaran';
                document.getElementById('error-metode').classList.remove('hidden');
                isValid = false;
            }

            return isValid;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const dateInput = document.getElementById('tglPesan');
            const minDate = tomorrow.toISOString().split('T')[0];
            dateInput.min = minDate;
            if (!dateInput.value || dateInput.value < minDate) {
                dateInput.value = minDate;
            }

            document.getElementById('jumlahPax').addEventListener('input', function() {
                updateTotal();

                const value = parseInt(this.value);
                const min = parseInt(this.min);
                const errorElement = document.getElementById('error-jumlah');

                if (value < min) {
                    errorElement.textContent = `Jumlah pax minimal ${min}`;
                    errorElement.classList.remove('hidden');
                } else {
                    errorElement.classList.add('hidden');
                }
            });

            document.getElementById('pesanForm').addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

                return true;
            });
        });
    </script>
@endsection
