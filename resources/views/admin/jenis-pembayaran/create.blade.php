@extends('layouts.app')

@section('title', 'Tambah Metode Pembayaran')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-8">
        <div class="mb-8">
            <p class="text-2xl font-bold text-gray-700 mb-2">Tambahkan metode pembayaran baru ke sistem</p>
        </div>
        
        <!-- Form -->
        <form action="{{ route('admin.jenis-pembayaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-6">
                <!-- Nama Metode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Metode Pembayaran *
                    </label>
                    <input type="text" name="metode_pembayaran" value="{{ old('metode_pembayaran') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Contoh: Transfer Bank, Tunai, E-Wallet"
                           required>
                    @error('metode_pembayaran')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Detail Pembayaran (Optional) -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Detail Pembayaran (Opsional)</h3>
                    
                    <!-- No Rekening -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            No. Rekening
                        </label>
                        <input type="text" name="no_rek" value="{{ old('no_rek') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Contoh: 1234567890">
                    </div>
                    
                    <!-- Tempat Bayar -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tempat/Penyedia Pembayaran
                        </label>
                        <input type="text" name="tempat_bayar" value="{{ old('tempat_bayar') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Contoh: BCA, BRI, Mandiri, OVO, Dana">
                    </div>
                    
                    <!-- Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Logo (Opsional)
                        </label>
                        <input type="file" name="logo"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               accept="image/*">
                        <p class="text-xs text-gray-500 mt-1">Format: jpeg, png, jpg, gif | Maks: 2MB</p>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="flex justify-between pt-6">
                    <a href="{{ route('admin.jenis-pembayaran') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection