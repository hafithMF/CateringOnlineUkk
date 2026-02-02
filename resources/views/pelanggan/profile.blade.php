@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Sidebar Profil -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <!-- Profile Info -->
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto mb-4 relative">
                            @if ($pelanggan->foto)
                                <img src="{{ Storage::url('public/pelanggans/' . $pelanggan->foto) }}"
                                    alt="{{ $pelanggan->name_pelanggan }}" class="w-full h-full object-cover rounded-full">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-2xl font-bold">
                                        {{ strtoupper(substr($pelanggan->name_pelanggan, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <h2 class="text-xl font-bold text-gray-800">{{ $pelanggan->name_pelanggan }}</h2>
                        <p class="text-gray-600">{{ $pelanggan->email }}</p>
                    </div>

                    <!-- Stats -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Total Pesanan</p>
                                <p class="text-xl font-bold text-gray-800">{{ $totalPesanan }}</p>
                            </div>
                            <i class="fas fa-shopping-bag text-emerald-500 text-xl"></i>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Pesanan Aktif</p>
                                <p class="text-xl font-bold text-gray-800">{{ $pesananAktif }}</p>
                            </div>
                            <i class="fas fa-clock text-blue-500 text-xl"></i>
                        </div>
                    </div>

                    <!-- Menu -->
                    <div class="space-y-2">
                        <a href="{{ route('edit-profile') }}"
                            class="flex items-center p-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 rounded-lg transition">
                            <i class="fas fa-user-edit mr-3"></i>
                            <span>Edit Profil</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center p-3 text-red-600 hover:bg-red-50 rounded-lg transition">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="lg:col-span-2">
                <!-- Personal Info -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Informasi Pribadi</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                            <p class="text-gray-800">{{ $pelanggan->name_pelanggan }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-800">{{ $pelanggan->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Telepon</label>
                            <p class="text-gray-800">{{ $pelanggan->telepon ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                            <p class="text-gray-800">
                                {{ $pelanggan->tgl_lahir ? date('d M Y', strtotime($pelanggan->tgl_lahir)) : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Alamat</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Utama</label>
                            <p class="text-gray-800">{{ $pelanggan->alamat1 ?? '-' }}</p>
                        </div>

                        @if ($pelanggan->alamat2)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Alternatif 1</label>
                                <p class="text-gray-800">{{ $pelanggan->alamat2 }}</p>
                            </div>
                        @endif

                        @if ($pelanggan->alamat3)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Alternatif 2</label>
                                <p class="text-gray-800">{{ $pelanggan->alamat3 }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('pesanan-saya') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-shopping-bag text-emerald-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Pesanan Saya</h4>
                                <p class="text-sm text-gray-600">Lihat semua pesanan</p>
                            </div>
                        </a>

                        <a href="{{ route('paket') }}"
                            class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-box text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">Paket Catering</h4>
                                <p class="text-sm text-gray-600">Pesan catering baru</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
