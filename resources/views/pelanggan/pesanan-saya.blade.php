@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Pesanan Saya</h1>
        <p class="text-gray-600">Riwayat dan status pemesanan catering Anda</p>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
    <div class="mb-6 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    @if($pemesanans && $pemesanans->count() > 0)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No. Resi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Paket
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
                        @foreach($pemesanans as $pemesanan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $pemesanan->no_resi ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($pemesanan->detailPemesanans && $pemesanan->detailPemesanans->count() > 0)
                                            {{ $pemesanan->detailPemesanans->first()->paket->name_paket ?? 'Paket tidak ditemukan' }}
                                        @else
                                            Paket tidak ditemukan
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $pemesanan->created_at ? $pemesanan->created_at->format('d M Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-emerald-600">
                                        Rp {{ number_format($pemesanan->total_bayar ?? 0, 0, ',', '.') }}
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
                                        $status = $pemesanan->status_pesan ?? 'Menunggu Konfirmasi';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('detail-pesanan', $pemesanan->id) }}" 
                                       class="text-emerald-600 hover:text-emerald-900 mr-3">
                                        <i class="fas fa-eye mr-1"></i> Detail
                                    </a>
                                    @if($pemesanan->status_pesan == 'Menunggu Konfirmasi')
                                    <form action="{{ route('pesanan.cancel', $pemesanan->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                                class="text-rose-600 hover:text-rose-900">
                                            <i class="fas fa-times mr-1"></i> Batalkan
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($pemesanans->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pemesanans->links() }}
            </div>
            @endif
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-bag text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Belum ada pesanan</h3>
            <p class="text-gray-600 mb-6">Mulai pesan catering pertama Anda</p>
            <a href="{{ route('paket') }}" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-3 rounded-lg hover:from-emerald-600 hover:to-teal-700">
                <i class="fas fa-box mr-2"></i> Lihat Paket
            </a>
        </div>
    @endif
</div>

<script>
console.log('Pemesanans data:', @json($pemesanans));
</script>
@endsection