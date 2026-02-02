@extends('layouts.app')

@section('title', 'Kelola Pengiriman')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-gray-600">Kelola pengiriman pesanan catering</p>
        </div>
        
        <a href="{{ route('admin.pengiriman.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Buat Pengiriman
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Pengiriman</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ \App\Models\Pengiriman::count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-truck text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Sedang Dikirim</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ \App\Models\Pengiriman::where('status_kirim', 'Sedang Dikirim')->count() }}
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
                    <p class="text-sm text-gray-600">Selesai Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ \App\Models\Pengiriman::where('status_kirim', 'Tiba di Tujuan')->whereDate('updated_at', date('Y-m-d'))->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Table -->
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
                            Kurir
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status Pengiriman
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Kirim
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengirimans as $pengiriman)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $pengiriman->pemesanan->no_resi }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $pengiriman->pemesanan->pelanggan->name_pelanggan }}
                            </div>
                            <div class="text-xs text-gray-500 truncate max-w-xs">
                                {{ $pengiriman->pemesanan->pelanggan->alamat1 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pengiriman->kurir)
                                <div class="text-sm text-gray-900">{{ $pengiriman->kurir->name }}</div>
                                <div class="text-xs text-gray-500">{{ $pengiriman->kurir->telepon ?? '-' }}</div>
                            @else
                                <span class="text-sm text-gray-500">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'Sedang Dikirim' => 'bg-yellow-100 text-yellow-800',
                                    'Tiba di Tujuan' => 'bg-emerald-100 text-emerald-800',
                                ];
                            @endphp
                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$pengiriman->status_kirim] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $pengiriman->status_kirim }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @if($pengiriman->tgl_kirim)
                                    {{ \Carbon\Carbon::parse($pengiriman->tgl_kirim)->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('admin.pengiriman.show', $pengiriman->id) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            
                            @if(!$pengiriman->kurir)
                            <button onclick="assignKurir('{{ $pengiriman->id }}')" 
                                    class="text-emerald-600 hover:text-emerald-900">
                                <i class="fas fa-user-plus"></i> Assign
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
        
        <!-- Pagination -->
        @if($pengirimans->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $pengirimans->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Assign Kurir Modal -->
<div id="assignKurirModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Assign Kurir</h3>
                
                <form id="assignKurirForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kurir</label>
                        <select name="id_user" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            <option value="">Pilih Kurir</option>
                            @php
                                $kurirs = \App\Models\User::where('level', 'kurir')->get();
                            @endphp
                            @foreach($kurirs as $kurir)
                                <option value="{{ $kurir->id }}">{{ $kurir->name }} ({{ $kurir->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeAssignKurirModal()" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Assign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function assignKurir(pengirimanId) {
    const form = document.getElementById('assignKurirForm');
    form.action = `/admin/pengiriman/${pengirimanId}/assign`;
    document.getElementById('assignKurirModal').classList.remove('hidden');
}

function closeAssignKurirModal() {
    document.getElementById('assignKurirModal').classList.add('hidden');
}

document.getElementById('assignKurirModal').addEventListener('click', function(e) {
    if (e.target.id === 'assignKurirModal') {
        closeAssignKurirModal();
    }
});
</script>
@endsection