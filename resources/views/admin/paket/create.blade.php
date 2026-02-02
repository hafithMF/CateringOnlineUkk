@extends('layouts.app')

@section('title', 'Tambah Paket')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-md p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Tambah paket catering baru ke sistem</h1>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.paket.store') }}" method="POST" enctype="multipart/form-data" id="paketForm">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Paket
                        </label>
                        <input type="text" name="name_paket" value="{{ old('name_paket') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            placeholder="Contoh: Paket Pernikahan Premium" required>
                        @error('name_paket')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis & Kategori -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis
                            </label>
                            <select name="jenis"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                required>
                                <option value="">Pilih Jenis</option>
                                <option value="Prasmanan" {{ old('jenis') == 'Prasmanan' ? 'selected' : '' }}>Prasmanan
                                </option>
                                <option value="Box" {{ old('jenis') == 'Box' ? 'selected' : '' }}>Box</option>
                            </select>
                            @error('jenis')
                                <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori
                            </label>
                            <select name="kategori"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                required>
                                <option value="">Pilih Kategori</option>
                                <option value="Pernikahan" {{ old('kategori') == 'Pernikahan' ? 'selected' : '' }}>
                                    Pernikahan</option>
                                <option value="Selamatan" {{ old('kategori') == 'Selamatan' ? 'selected' : '' }}>Selamatan
                                </option>
                                <option value="Ulang Tahun" {{ old('kategori') == 'Ulang Tahun' ? 'selected' : '' }}>Ulang
                                    Tahun</option>
                                <option value="Studi Tour" {{ old('kategori') == 'Studi Tour' ? 'selected' : '' }}>Studi
                                    Tour</option>
                                <option value="Rapat" {{ old('kategori') == 'Rapat' ? 'selected' : '' }}>Rapat</option>
                            </select>
                            @error('kategori')
                                <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Jumlah Pax & Harga -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Pax (Minimum)
                            </label>
                            <input type="number" name="jumlah_pax" value="{{ old('jumlah_pax') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                placeholder="10" min="1" max="999999" required>
                            @error('jumlah_pax')
                                <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Harga per Pax (Rp)
                            </label>
                            <input type="text" name="harga_paket_display" id="harga_paket_display" 
                                value="{{ old('harga_paket_display', old('harga_paket')) }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-transparent harga-input"
                                placeholder="50,000" required>
                            <input type="hidden" name="harga_paket" id="harga_paket" value="{{ old('harga_paket') }}">
                            @error('harga_paket')
                                <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Paket
                        </label>
                        <textarea name="deskripsi" rows="4"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                            placeholder="Deskripsikan paket catering ini secara detail..." required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-rose-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto Paket -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Paket (Opsional)
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach (['foto1' => 'Foto Utama', 'foto2' => 'Foto 2', 'foto3' => 'Foto 3'] as $field => $label)
                                <div>
                                    <label class="block text-sm text-gray-600 mb-2">{{ $label }}</label>
                                    <input type="file" name="{{ $field }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                        accept="image/*">
                                    <div class="image-preview-{{ $field }} mt-2"></div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            Format: JPG, PNG, GIF. Maks: 2MB per foto
                        </p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-between pt-6">
                        <a href="{{ route('admin.paket') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Kembali
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-lg hover:from-emerald-600 hover:to-teal-700">
                            Simpan Paket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatRupiah(angka) {
            if (!angka) return '';
            let number_string = angka.toString().replace(/[^,\d]/g, '');
            let split = number_string.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            
            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        function convertToNumber(rupiah) {
            if (!rupiah) return 0;
            let clean = rupiah.toString().replace(/\./g, '').replace(',', '.');
            clean = clean.replace(/[^\d.]/g, '');
            return Math.round(parseFloat(clean) || 0);
        }

        document.getElementById('harga_paket_display').addEventListener('input', function(e) {
            let value = e.target.value;
            let formatted = formatRupiah(value);
            e.target.value = formatted;
            
            document.getElementById('harga_paket').value = convertToNumber(formatted);
        });

        document.getElementById('paketForm').addEventListener('submit', function(e) {
            let displayValue = document.getElementById('harga_paket_display').value;
            let hiddenValue = document.getElementById('harga_paket').value;
            
            if (!hiddenValue && displayValue) {
                document.getElementById('harga_paket').value = convertToNumber(displayValue);
            }
            
            let harga = document.getElementById('harga_paket').value;
            if (parseInt(harga) < 1000) {
                alert('Harga minimal adalah Rp 1.000');
                e.preventDefault();
                return false;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            let hargaValue = document.getElementById('harga_paket_display').value;
            if (hargaValue) {
                let formatted = formatRupiah(hargaValue);
                document.getElementById('harga_paket_display').value = formatted;
                document.getElementById('harga_paket').value = convertToNumber(formatted);
            }
        });

        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const fieldName = this.name;
                        const previewDiv = document.querySelector(`.image-preview-${fieldName}`);
                        previewDiv.innerHTML = `
                        <p class="text-xs text-gray-500 mb-1">Preview:</p>
                        <img src="${e.target.result}" 
                             class="w-full h-40 object-cover rounded-lg border border-gray-200">
                    `;
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection