@extends('layouts.app')

@section('title', 'Kelola Pemesanan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-gray-600">Kelola semua pesanan dari pelanggan</p>
        </div>
        <!-- Filters -->
        <div class="flex space-x-4">
            <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Semua Status</option>
                <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                <option value="Menunggu Kurir">Menunggu Kurir</option>
                <option value="Selesai">Selesai</option>
                <option value="Dibatalkan">Dibatalkan</option>
            </select>
            
            <input type="date" id="dateFilter" 
                   class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Menunggu Konfirmasi</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ \App\Models\Pemesanan::where('status_pesan', 'Menunggu Konfirmasi')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Menunggu Kurir</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ \App\Models\Pemesanan::where('status_pesan', 'Menunggu Kurir')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-truck text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Selesai</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ \App\Models\Pemesanan::where('status_pesan', 'Selesai')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ \App\Models\Pemesanan::whereDate('created_at', date('Y-m-d'))->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
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
                            Tanggal Pesan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Bayar
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
                    @forelse($pemesanans as $pemesanan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $pemesanan->no_resi }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $pemesanan->detailPemesanans->count() }} item
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $pemesanan->pelanggan->name_pelanggan }}</div>
                            <div class="text-xs text-gray-500">{{ $pemesanan->pelanggan->telepon }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($pemesanan->tgl_pesan)->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($pemesanan->created_at)->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-emerald-600">
                                Rp {{ number_format($pemesanan->total_bayar, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                    'Menunggu Kurir' => 'bg-purple-100 text-purple-800',
                                    'Selesai' => 'bg-emerald-100 text-emerald-800',
                                    'Dibatalkan' => 'bg-rose-100 text-rose-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$pemesanan->status_pesan] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $pemesanan->status_pesan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('admin.pemesanan.show', $pemesanan->id) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            
                            @if($pemesanan->status_pesan == 'Menunggu Konfirmasi')
                            <form action="{{ route('admin.pemesanan.status', $pemesanan->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="Menunggu Kurir">
                                <button type="submit" class="text-emerald-600 hover:text-emerald-900">
                                    <i class="fas fa-check"></i> Konfirmasi & Siap Kirim
                                </button>
                            </form>
                            @endif
                            
                            @if($pemesanan->status_pesan == 'Menunggu Kurir')
                            <form action="{{ route('admin.pemesanan.status', $pemesanan->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="status" value="Selesai">
                                <button type="submit" class="text-emerald-600 hover:text-emerald-900">
                                    <i class="fas fa-check-circle"></i> Tandai Selesai
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data pesanan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pemesanans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pemesanans->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    const date = document.getElementById('dateFilter').value;
    applyFilters(status, date);
});

document.getElementById('dateFilter').addEventListener('change', function() {
    const status = document.getElementById('statusFilter').value;
    const date = this.value;
    applyFilters(status, date);
});

function applyFilters(status, date) {
    let url = new URL(window.location.href);
    
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    
    if (date) {
        url.searchParams.set('date', date);
    } else {
        url.searchParams.delete('date');
    }
    
    window.location.href = url.toString();
}
</script>
@endsection