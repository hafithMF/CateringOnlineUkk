@extends('layouts.app')

@section('title', 'Dashboard Kurir')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Pengiriman -->
        <div class="bg-white rounded-xl shadow-md p-6 stat-card">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Pengiriman</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPengiriman }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-truck text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Sedang Dikirim -->
        <div class="bg-white rounded-xl shadow-md p-6 stat-card">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Sedang Dikirim</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $pengirimanAktif }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Deliveries -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Pengiriman Terbaru</h2>
            <a href="{{ route('kurir.pengiriman') }}" 
               class="text-blue-600 hover:text-blue-800 font-medium">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            No. Resi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Alamat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Kirim
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentDeliveries as $delivery)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $delivery->pemesanan->no_resi }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $delivery->pemesanan->pelanggan->name_pelanggan }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-xs truncate">
                                {{ $delivery->pemesanan->pelanggan->alamat1 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($delivery->tgl_kirim)
                                    {{ \Carbon\Carbon::parse($delivery->tgl_kirim)->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Sedang Dikirim' => 'bg-yellow-100 text-yellow-800',
                                    'Tiba di Tujuan' => 'bg-emerald-100 text-emerald-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$delivery->status_kirim] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $delivery->status_kirim }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('kurir.pengiriman.show', $delivery->id) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            @if($delivery->status_kirim == 'Sedang Dikirim')
                                <button onclick="openUpdateModal('{{ $delivery->id }}')" 
                                        class="text-emerald-600 hover:text-emerald-900">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data pengiriman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Update Status Pengiriman</h3>
                
                <form id="updateForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status_kirim" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="Tiba di Tujuan">Tiba di Tujuan</option>
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
                        <button type="button" onclick="closeModal()" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openUpdateModal(deliveryId) {
    const modal = document.getElementById('updateModal');
    const form = document.getElementById('updateForm');
    
    form.action = `/kurir/pengiriman/${deliveryId}/status`;
    
    modal.classList.remove('hidden');
}

function closeModal() {
    const modal = document.getElementById('updateModal');
    modal.classList.add('hidden');
}

document.getElementById('updateModal').addEventListener('click', function(e) {
    if (e.target.id === 'updateModal') {
        closeModal();
    }
});
</script>
@endsection