@extends('layouts.app')

@section('title', 'Laporan Owner')

@section('content')
    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6 stat-card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total Pendapatan</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-emerald-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 stat-card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total Pesanan</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPemesanan }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 stat-card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total Pelanggan</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPelanggan }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 stat-card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Total Paket</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPaket }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Pendapatan Per Bulan ({{ date('Y') }})</h2>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Summary -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Ringkasan</h2>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Pesanan Sukses</span>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $recentPemesanans->where('status_pesan', 'Selesai')->count() }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-emerald-600 h-2 rounded-full"
                                style="width: {{ ($recentPemesanans->where('status_pesan', 'Selesai')->count() / max($recentPemesanans->count(), 1)) * 100 }}%">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Pesanan Diproses</span>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $recentPemesanans->whereIn('status_pesan', ['Sedang Diproses', 'Menunggu Kurir'])->count() }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full"
                                style="width: {{ ($recentPemesanans->whereIn('status_pesan', ['Sedang Diproses', 'Menunggu Kurir'])->count() / max($recentPemesanans->count(), 1)) * 100 }}%">
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Pesanan Batal</span>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $recentPemesanans->where('status_pesan', 'Dibatalkan')->count() }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full"
                                style="width: {{ ($recentPemesanans->where('status_pesan', 'Dibatalkan')->count() / max($recentPemesanans->count(), 1)) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">10 Pesanan Terbaru</h2>
                <a href="{{ route('admin.pemesanan') }}" class="text-blue-600 hover:text-blue-800 font-medium">
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
                                Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
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
                        @foreach ($recentPemesanans as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->no_resi }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->pelanggan->name_pelanggan }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-emerald-600">
                                        Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-800',
                                            'Sedang Diproses' => 'bg-blue-100 text-blue-800',
                                            'Menunggu Kurir' => 'bg-purple-100 text-purple-800',
                                            'Selesai' => 'bg-emerald-100 text-emerald-800',
                                            'Dibatalkan' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status_pesan] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $order->status_pesan }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.pemesanan.show', $order->id) }}"
                                        class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Section -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Ekspor Laporan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border border-gray-200 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-pdf text-rose-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">PDF Report</h3>
                    <p class="text-gray-600 text-sm mb-4">Ekspor laporan bulanan dalam format PDF</p>
                    <button onclick="alert('Fitur ekspor akan segera tersedia')"
                        class="bg-rose-600 text-white px-4 py-2 rounded-lg hover:bg-rose-700">
                        <i class="fas fa-download mr-2"></i> Unduh PDF
                    </button>
                </div>

                <div class="border border-gray-200 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-excel text-emerald-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Excel Report</h3>
                    <p class="text-gray-600 text-sm mb-4">Ekspor data transaksi dalam format Excel</p>
                    <button onclick="alert('Fitur ekspor akan segera tersedia')"
                        class="bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700">
                        <i class="fas fa-download mr-2"></i> Unduh Excel
                    </button>
                </div>

                <div class="border border-gray-200 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 mb-2">Analytics</h3>
                    <p class="text-gray-600 text-sm mb-4">Lihat analisis data lebih detail</p>
                    <button onclick="alert('Fitur analytics akan segera tersedia')"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-chart-bar mr-2"></i> Lihat Analisis
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const revenueData = @json($pendapatanPerBulan);
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            const revenuePerMonth = Array(12).fill(0);

            revenueData.forEach(item => {
                revenuePerMonth[item.bulan - 1] = item.total;
            });

            const revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: revenuePerMonth,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
