@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-md p-6 md:p-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-600 mb-2">Ubah data profil Anda</h1>
        </div>
        
        <!-- Form -->
        <form action="{{ route('staff.profile.update') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-gray-400"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                           placeholder="Nama lengkap"
                           required>
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-gray-400"></i>
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                           placeholder="email@catering.com"
                           required>
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Current Level -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-2 text-gray-400"></i>
                        Jabatan
                    </label>
                    <div class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 text-gray-700">
                        {{ ucfirst($user->level) }}
                    </div>
                    <p class="text-sm text-gray-500 mt-1">* Jabatan tidak dapat diubah</p>
                </div>
                
                <!-- Password Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">
                        <i class="fas fa-lock mr-2"></i> Ubah Password (Opsional)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru
                            </label>
                            <input type="password" name="password"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                   placeholder="Kosongkan jika tidak diubah">
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition"
                                   placeholder="Ulangi password">
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Isi hanya jika ingin mengubah password
                    </p>
                </div>
                
                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 pt-8 border-t">
                    <a href="{{ route('staff.profile') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-center transition">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Profil
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>Update Profil
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection