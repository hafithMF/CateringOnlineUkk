@extends('layouts.app')

@section('title', 'Kelola Metode Pembayaran')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-gray-600">Kelola semua metode pembayaran yang tersedia</p>
        </div>
        
        <a href="{{ route('admin.jenis-pembayaran.create') }}" 
           class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-4 py-2 rounded-lg hover:from-emerald-600 hover:to-teal-700">
            <i class="fas fa-plus mr-2"></i> Tambah Metode
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Metode</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $jenisPembayarans->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-credit-card text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Dengan Detail</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $jenisPembayarans->filter(function($jenis) {
                            return $jenis->detailJenisPembayarans && $jenis->detailJenisPembayarans->count() > 0;
                        })->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-list text-emerald-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Aktif</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        {{ $jenisPembayarans->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Metode Pembayaran -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Metode Pembayaran
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Detail Pembayaran
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jenisPembayarans as $jenis)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $jenis->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @php
                                    $detail = $jenis->detailJenisPembayarans->first();
                                @endphp
                                @if($detail && $detail->logo)
                                    <img src="{{ Storage::url('public/jenis-pembayaran/' . $detail->logo) }}" 
                                         alt="Logo" class="w-8 h-8 mr-3 rounded">
                                @else
                                    <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center mr-3">
                                        <i class="fas fa-money-bill-wave text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $jenis->metode_pembayaran }}</div>
                                    <div class="text-xs text-gray-500">
                                        Dibuat: {{ $jenis->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($jenis->detailJenisPembayarans && $jenis->detailJenisPembayarans->count() > 0)
                                <div class="space-y-1">
                                    @foreach($jenis->detailJenisPembayarans as $detail)
                                        <div class="text-sm text-gray-800">
                                            {{ $detail->tempat_bayar ?? 'Bank' }}: 
                                            <span class="font-mono text-gray-600">{{ $detail->no_rek ?? '-' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-sm text-gray-500 italic">Tidak ada detail</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-blue-600">
                                {{ $jenis->pemesanans ? $jenis->pemesanans->count() : 0 }} pesanan
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('admin.jenis-pembayaran.edit', $jenis->id) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @if($jenis->pemesanans->count() == 0)
                                <form action="{{ route('admin.jenis-pembayaran.destroy', $jenis->id) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Hapus metode pembayaran ini?')"
                                            class="text-rose-600 hover:text-rose-900">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400" title="Tidak dapat dihapus karena sudah digunakan">
                                    <i class="fas fa-trash"></i> Hapus
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data metode pembayaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection