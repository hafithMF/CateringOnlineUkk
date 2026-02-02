<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function profile()
    {
        $user = Auth::guard('web')->user();
        return view('staff.profile', [
            'user' => $user,
            'title' => 'Profil ' . ucfirst($user->level)
        ]);
    }

    public function editProfile()
    {
        $user = Auth::guard('web')->user();
        return view('staff.edit-profile', [
            'user' => $user,
            'title' => 'Edit Profil'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();

        $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $data = $request->only(['name', 'email']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect('/staff/profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function index()
    {
        $users = User::where('id', '!=', Auth::id())
            ->orderBy('level')
            ->orderBy('name')
            ->paginate(15);

        return view('owner.users.index', [
            'users' => $users,
            'title' => 'Manajemen User'
        ]);
    }

    public function create()
    {
        return view('owner.users.create', [
            'title' => 'Tambah User Baru'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'level' => 'required|in:admin,kurir',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'level' => $request->level,
        ]);

        return redirect('/owner/users')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        if ($user->isOwner() && $user->id != Auth::id()) {
            return redirect('/owner/users')->with('error', 'Tidak dapat mengedit akun owner lain.');
        }

        return view('owner.users.edit', [
            'user' => $user, 
            'title' => 'Edit User'
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->isOwner() && $user->id != Auth::id()) {
            return redirect('/owner/users')->with('error', 'Tidak dapat mengedit akun owner lain.');
        }

        $request->validate([
            'name' => 'required|string|max:30',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'level' => 'required|in:admin,owner,kurir',
        ]);

        $data = $request->only(['name', 'email', 'level']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect('/owner/users')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id == Auth::id()) {
            return redirect('/owner/users')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if ($user->isOwner()) {
            return redirect('/owner/users')->with('error', 'Tidak dapat menghapus akun owner.');
        }

        $user->delete();

        return redirect('/owner/users')->with('success', 'User berhasil dihapus.');
    }

    public function reports()
    {
        $totalPendapatan = \App\Models\Pemesanan::where('status_pesan', 'Selesai')
            ->sum('total_bayar');

        $totalPemesanan = \App\Models\Pemesanan::count();
        $totalPelanggan = Pelanggan::count();
        $totalPaket = \App\Models\Paket::count();

        $pendapatanPerBulan = \App\Models\Pemesanan::selectRaw(
            "EXTRACT(MONTH FROM created_at) as bulan, SUM(total_bayar) as total"
        )
            ->where('status_pesan', 'Selesai')
            ->whereYear('created_at', date('Y'))
            ->groupByRaw("EXTRACT(MONTH FROM created_at)")
            ->orderBy('bulan', 'asc')
            ->get();

        $recentPemesanans = \App\Models\Pemesanan::with('pelanggan')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('owner.reports.index', [
            'title' => 'Laporan Owner',
            'totalPendapatan' => $totalPendapatan,
            'totalPemesanan' => $totalPemesanan,
            'totalPelanggan' => $totalPelanggan,
            'totalPaket' => $totalPaket,
            'pendapatanPerBulan' => $pendapatanPerBulan,
            'recentPemesanans' => $recentPemesanans
        ]);
    }

    public function exportReports()
    {
        return response()->download('path/to/report.pdf');
    }

    public function pelangganIndex()
    {
        $pelanggans = Pelanggan::withCount('pemesanans')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.pelanggan.index', [
            'pelanggans' => $pelanggans,
            'title' => 'Manajemen Pelanggan'
        ]);
    }

    public function pelangganShow($id)
    {
        $pelanggan = Pelanggan::with(['pemesanans' => function ($query) {
            $query->with(['detailPemesanans.paket'])->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        return view('admin.pelanggan.show', [
            'pelanggan' => $pelanggan,
            'title' => 'Detail Pelanggan: ' . $pelanggan->name_pelanggan
        ]);
    }
}
