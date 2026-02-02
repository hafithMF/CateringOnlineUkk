@extends('layouts.app')

@section('title', 'Detail Pengiriman')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <p class="text-gray-600">No. Resi: {{ $pengiriman->pemesanan->no_resi }}</p>
            </div>
            <a href="{{ route('admin.pengiriman') }}"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pengiriman</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                            @php
                                $statusColors = [
                                    'Sedang Dikirim' => 'bg-amber-100 text-amber-800',
                                    'Tiba di Tujuan' => 'bg-emerald-100 text-emerald-800',
                                ];
                            @endphp
                            <span
                                class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColors[$pengiriman->status_kirim] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $pengiriman->status_kirim }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kurir</label>
                            <p class="text-gray-800">
                                @if ($pengiriman->kurir)
                                    {{ $pengiriman->kurir->name }}
                                @else
                                    <span class="text-gray-400">Belum ditugaskan</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Kirim</label>
                            <p class="text-gray-800">
                                @if ($pengiriman->tgl_kirim)
                                    {{ \Carbon\Carbon::parse($pengiriman->tgl_kirim)->format('d F Y H:i') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Tiba</label>
                            <p class="text-gray-800">
                                @if ($pengiriman->tgl_tiba)
                                    {{ \Carbon\Carbon::parse($pengiriman->tgl_tiba)->format('d F Y H:i') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($pengiriman->bukti_foto)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Bukti Foto</label>
                            @if ($pengiriman->bukti_foto_url)
                                <img src="{{ $pengiriman->bukti_foto_url }}" alt="Bukti Pengiriman"
                                    class="w-64 h-64 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-90"
                                    onclick="window.open('{{ $pengiriman->bukti_foto_url }}', '_blank')">
                            @else
                                <div class="text-center p-4 border border-gray-200 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 mb-2"></i>
                                    <p class="text-gray-600">File tidak ditemukan</p>
                                    <p class="text-sm text-gray-500">Nama file: {{ $pengiriman->bukti_foto }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Pemesanan Details -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Pemesanan</h2>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Paket</label>
                        @foreach ($pengiriman->pemesanan->detailPemesanans as $detail)
                            <div class="border border-gray-200 rounded-lg p-4 mb-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-gray-800">{{ $detail->paket->name_paket }}</h3>
                                        <p class="text-gray-600 text-sm">{{ $detail->paket->jenis }} -
                                            {{ $detail->paket->kategori }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-800">{{ $detail->jumlah_pax }} pax</p>
                                        <p class="text-emerald-600 font-bold">Rp
                                            {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-800">Total Bayar</span>
                                <span class="text-xl font-bold text-emerald-600">
                                    Rp {{ number_format($pengiriman->pemesanan->total_bayar, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pelanggan</h2>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama</label>
                            <p class="text-gray-800">{{ $pengiriman->pemesanan->pelanggan->name_pelanggan }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Telepon</label>
                            <p class="text-gray-800">{{ $pengiriman->pemesanan->pelanggan->telepon }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-800">{{ $pengiriman->pemesanan->pelanggan->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Alamat</label>
                            <p class="text-gray-800 text-sm">{{ $pengiriman->pemesanan->pelanggan->alamat1 }}</p>
                            @if ($pengiriman->pemesanan->pelanggan->alamat2)
                                <p class="text-gray-800 text-sm">{{ $pengiriman->pemesanan->pelanggan->alamat2 }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Kurir Modal -->
    <div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Assign Kurir</h3>

                    <form action="{{ route('admin.pengiriman.assign', $pengiriman->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kurir</label>
                            <select name="id_user"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                                <option value="">-- Pilih Kurir --</option>
                                @foreach (\App\Models\User::where('level', 'kurir')->get() as $kurir)
                                    <option value="{{ $kurir->id }}"
                                        {{ $pengiriman->id_user == $kurir->id ? 'selected' : '' }}>
                                        {{ $kurir->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeAssignModal()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                Assign
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Update Status Pengiriman</h3>

                    <form action="{{ route('admin.pengiriman.status', $pengiriman->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status_kirim"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    required>
                                    <option value="Tiba di Tujuan"
                                        {{ $pengiriman->status_kirim == 'Tiba di Tujuan' ? 'selected' : '' }}>
                                        Tiba di Tujuan
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Foto (Opsional)</label>
                                <input type="file" name="bukti_foto"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">Upload foto bukti pengiriman</p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" onclick="closeStatusModal()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAssignModal() {
            document.getElementById('assignModal').classList.remove('hidden');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
        }

        function openStatusModal() {
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.getElementById('assignModal').addEventListener('click', function(e) {
            if (e.target.id === 'assignModal') {
                closeAssignModal();
            }
        });

        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target.id === 'statusModal') {
                closeStatusModal();
            }
        });
    </script>
@endsection
