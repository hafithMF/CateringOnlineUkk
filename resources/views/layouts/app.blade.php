<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Catering Online')</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar-active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .table-hover tbody tr:hover {
            background-color: #f9fafb;
        }

        html,
        body {
            height: 100%;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 flex flex-col min-h-screen">
    @if (Auth::guard('web')->check())
        <div class="flex flex-1">
            <!-- Sidebar dengan warna hijau seperti halaman depan -->
            <div class="w-64 bg-gradient-to-b from-emerald-500 to-teal-600 min-h-screen fixed">
                <div class="p-6">
                    <!-- Logo -->
                    <a href="{{ Auth::user()->getRedirectRoute() }}" class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                            <i class="fas fa-utensils text-emerald-600 text-xl"></i>
                        </div>
                        <span class="text-white font-bold text-xl">Catering Online</span>
                    </a>

                    <!-- Navigation -->
                    <nav class="space-y-2">
                        @if (Auth::user()->isOwner())
                            <!-- Owner Menu -->
                            <a href="{{ route('owner.reports') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('owner/reports') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-chart-bar w-5"></i>
                                <span>Laporan</span>
                            </a>

                            <a href="{{ route('owner.users') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('owner/users*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-users w-5"></i>
                                <span>Manajemen User</span>
                            </a>

                            <a href="{{ route('admin.pemesanan') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('admin/pemesanan*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-shopping-cart w-5"></i>
                                <span>Pemesanan</span>
                            </a>

                            <a href="{{ route('admin.pengiriman') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('admin/pengiriman*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-truck w-5"></i>
                                <span>Pengiriman</span>
                            </a>

                            <a href="{{ route('admin.paket') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('admin/paket*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-box w-5"></i>
                                <span>Paket</span>
                            </a>

                            <a href="{{ route('admin.pelanggan') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('admin/pelanggan*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-user-friends w-5"></i>
                                <span>Pelanggan</span>
                            </a>

                            <a href="{{ route('admin.jenis-pembayaran') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('admin/jenis-pembayaran*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-credit-card w-5"></i>
                                <span>Metode Pembayaran</span>
                            </a>
                        @elseif(Auth::user()->isAdmin())
                            <!-- Admin Menu -->
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('dashboard') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-tachometer-alt w-5"></i>
                                <span>Dashboard</span>
                            </a>

                            <a href="{{ route('admin.pemesanan') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('admin/pemesanan*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-shopping-cart w-5"></i>
                                <span>Pemesanan</span>
                            </a>

                            <a href="{{ route('admin.pengiriman') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('admin/pengiriman*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-truck w-5"></i>
                                <span>Pengiriman</span>
                            </a>

                            <a href="{{ route('admin.paket') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('admin/paket*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-box w-5"></i>
                                <span>Paket</span>
                            </a>

                            <a href="{{ route('admin.pelanggan') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('admin/pelanggan*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-user-friends w-5"></i>
                                <span>Pelanggan</span>
                            </a>
                        @elseif(Auth::user()->isKurir())
                            <!-- Kurir Menu -->
                            <a href="{{ route('kurir.dashboard') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('kurir/dashboard') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-tachometer-alt w-5"></i>
                                <span>Dashboard</span>
                            </a>

                            <a href="{{ route('kurir.pengiriman') }}"
                                class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('kurir/pengiriman*') ? 'sidebar-active' : '' }}">
                                <i class="fas fa-truck w-5"></i>
                                <span>Pengiriman Saya</span>
                            </a>
                        @endif

                        <a href="{{ route('staff.profile') }}"
                            class="flex items-center space-x-3 px-4 py-3 text-white rounded-lg hover:bg-emerald-400/20 {{ request()->is('staff/profile*') ? 'sidebar-active' : '' }}">
                            <i class="fas fa-user-circle w-5"></i>
                            <span>Profil</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="ml-64 flex-1 flex flex-col">
                <!-- Top Navbar -->
                <div class="bg-white shadow-md">
                    <div class="px-8 py-4 flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800">@yield('title')</h1>

                        <!-- User Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center space-x-3 focus:outline-none">
                                <div class="text-right">
                                    <p class="font-medium text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-600">{{ ucfirst(Auth::user()->level) }}</p>
                                </div>
                                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-emerald-600"></i>
                                </div>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <a href="{{ route('staff.profile') }}"
                                    class="block px-4 py-3 text-gray-700 hover:bg-gray-50 border-b">
                                    <i class="fas fa-user-circle mr-2"></i>Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-3 text-red-600 hover:bg-gray-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Area -->
                <main class="p-8 flex-1">
                    @if (session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </main>

                <!-- Footer untuk Admin Layout -->
                <footer class="bg-gray-800 text-white py-6">
                    <div class="container mx-auto px-8">
                        <div class="text-center">
                            <p class="text-gray-300 mb-2">
                                Catering Online Management System
                            </p>
                            <p class="text-gray-400 text-sm">
                                &copy; {{ date('Y') }} CateringOnline. All rights reserved.
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    @elseif(Auth::guard('pelanggan')->check())
        <div class="flex flex-col min-h-screen">
            <nav class="bg-white shadow-md">
                <div class="container mx-auto px-4 py-4">
                    <div class="flex justify-between items-center">
                        <!-- Logo -->
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-white text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold text-gray-800">CateringOnline</span>
                        </a>

                        <!-- Menu Pelanggan -->
                        <div class="hidden md:flex items-center space-x-8">
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                                <i class="fas fa-home mr-2"></i>Beranda
                            </a>
                            <a href="{{ route('paket') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                                <i class="fas fa-box mr-2"></i>Paket
                            </a>
                            <a href="{{ route('pesanan-saya') }}"
                                class="text-gray-700 hover:text-emerald-600 font-medium">
                                <i class="fas fa-shopping-bag mr-2"></i>Pesanan Saya
                            </a>
                            <div class="relative group">
                                <button class="flex items-center space-x-2 focus:outline-none">
                                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-emerald-600"></i>
                                    </div>
                                    <span class="text-gray-700 hover:text-emerald-600 font-medium">
                                        {{ Auth::guard('pelanggan')->user()->name_pelanggan }}
                                    </span>
                                </button>
                                
                                <!-- Dropdown Menu untuk Pelanggan -->
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                    <a href="{{ route('profile') }}" 
                                       class="block px-4 py-3 text-gray-700 hover:bg-gray-50 border-b">
                                        <i class="fas fa-user-circle mr-2"></i>Profil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="block w-full text-left px-4 py-3 text-red-600 hover:bg-gray-50">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-1">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-gray-800 text-white py-8">
                <div class="container mx-auto px-4">
                    <div class="text-center">
                        <div class="flex items-center justify-center space-x-2 mb-4">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-white text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold">CateringOnline</span>
                        </div>
                        <p class="text-gray-300 mb-4">
                            Melayani kebutuhan catering Anda dengan kualitas terbaik dan harga terjangkau.
                        </p>
                        <p class="text-gray-400 text-sm">
                            &copy; {{ date('Y') }} CateringOnline. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    @else
        <div class="flex flex-col min-h-screen">
            <nav class="bg-white shadow-md">
                <div class="container mx-auto px-4 py-4">
                    <div class="flex justify-between items-center">
                        <!-- Logo -->
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-white text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold text-gray-800">CateringOnline</span>
                        </a>

                        <!-- Menu untuk pengunjung -->
                        <div class="hidden md:flex items-center space-x-8">
                            <a href="{{ route('home') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                                <i class="fas fa-home mr-2"></i>Beranda
                            </a>
                            <a href="{{ route('paket') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                                <i class="fas fa-box mr-2"></i>Paket
                            </a>
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-emerald-600 font-medium">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-2 rounded-lg hover:from-emerald-600 hover:to-teal-700">
                                <i class="fas fa-user-plus mr-2"></i>Daftar
                            </a>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-1">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-gray-800 text-white py-8">
                <div class="container mx-auto px-4">
                    <div class="text-center">
                        <div class="flex items-center justify-center space-x-2 mb-4">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-utensils text-white text-xl"></i>
                            </div>
                            <span class="text-2xl font-bold">CateringOnline</span>
                        </div>
                        <p class="text-gray-300 mb-4">
                            Melayani kebutuhan catering Anda dengan kualitas terbaik dan harga terjangkau.
                        </p>
                        <p class="text-gray-400 text-sm">
                            &copy; {{ date('Y') }} CateringOnline. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    @endif

    <script>
        setTimeout(() => {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>