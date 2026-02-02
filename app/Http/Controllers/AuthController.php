<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pelanggan;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Cek jika sudah login sebagai user (staff)
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            return redirect($user->getRedirectRoute());
        }
        
        // Cek jika sudah login sebagai pelanggan
        if (Auth::guard('pelanggan')->check()) {
            return redirect('/');
        }
        
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            return redirect($user->getRedirectRoute());
        }
        
        if (Auth::guard('pelanggan')->check()) {
            return redirect('/');
        }
        
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        // Coba login sebagai User (Staff) terlebih dahulu
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::guard('web')->user();
            return redirect()->intended($user->getRedirectRoute())->with('success', 'Login berhasil!');
        }

        // Jika bukan staff, coba login sebagai Pelanggan
        if (Auth::guard('pelanggan')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name_pelanggan' => 'required|string|max:100',
            'email' => 'required|email|unique:pelanggans,email',
            'password' => 'required|string|min:6|confirmed',
            'telepon' => 'required|string|max:15',
            'alamat1' => 'required|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'alamat2' => 'nullable|string|max:255',
            'alamat3' => 'nullable|string|max:255',
        ]);

        $pelanggan = Pelanggan::create([
            'name_pelanggan' => $request->name_pelanggan,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telepon' => $request->telepon,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2 ?? null,
            'alamat3' => $request->alamat3 ?? null,
            'tgl_lahir' => $request->tgl_lahir,
        ]);

        Auth::guard('pelanggan')->login($pelanggan);
        $request->session()->regenerate();

        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang, ' . $pelanggan->name_pelanggan);
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('success', 'Logout berhasil!');
        } 
        
        if (Auth::guard('pelanggan')->check()) {
            Auth::guard('pelanggan')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('success', 'Logout berhasil!');
        }

        return redirect('/');
    }

    public function dashboard()
    {
        // Redirect ke dashboard yang sesuai
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            
            if ($user->isOwner()) {
                return redirect()->route('owner.reports');
            } elseif ($user->isAdmin()) {
                $totalPelanggan = Pelanggan::count();
                $totalPemesanan = \App\Models\Pemesanan::count();
                $totalPaket = \App\Models\Paket::count();
                $totalPendapatan = \App\Models\Pemesanan::sum('total_bayar');
                
                $recentOrders = \App\Models\Pemesanan::with('pelanggan')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                return view('admin.dashboard', [
                    'user' => $user,
                    'title' => 'Dashboard Admin',
                    'totalPelanggan' => $totalPelanggan,
                    'totalPemesanan' => $totalPemesanan,
                    'totalPaket' => $totalPaket,
                    'totalPendapatan' => $totalPendapatan,
                    'recentOrders' => $recentOrders
                ]);
            } elseif ($user->isKurir()) {
                return redirect()->route('kurir.dashboard');
            }
        }
        
        // Jika pelanggan, redirect ke home
        if (Auth::guard('pelanggan')->check()) {
            return redirect('/');
        }
        
        return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
    }

    public function kurirDashboard()
    {
        $kurir = Auth::guard('web')->user();
        
        if (!$kurir || !$kurir->isKurir()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }
        
        $totalPengiriman = \App\Models\Pengiriman::where('id_user', $kurir->id)->count();
        $pengirimanAktif = \App\Models\Pengiriman::where('id_user', $kurir->id)
            ->where('status_kirim', 'Sedang Dikirim')
            ->count();
        
        $recentDeliveries = \App\Models\Pengiriman::with('pemesanan.pelanggan')
            ->where('id_user', $kurir->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('kurir.dashboard', [
            'kurir' => $kurir,
            'title' => 'Dashboard Kurir',
            'totalPengiriman' => $totalPengiriman,
            'pengirimanAktif' => $pengirimanAktif,
            'recentDeliveries' => $recentDeliveries
        ]);
    }
}