<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pelanggan;
use App\Models\Paket;
use App\Models\Pemesanan;
use App\Models\JenisPembayaran;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PelangganController extends Controller
{
    public function home()
    {
        $paketPopuler = Paket::orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
            
        $kategoriList = Paket::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->toArray();
        
        if (empty($kategoriList)) {
            $kategoriList = ['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Rapat'];
        }
        
        return view('pelanggan.home', [
            'paketPopuler' => $paketPopuler,
            'kategoriList' => $kategoriList, 
            'title' => 'Catering Online - Beranda'
        ]);
    }

    public function paket(Request $request)
    {
        $query = Paket::query();
        if ($request->has('kategori') && $request->kategori != 'semua') {
            $query->where('kategori', $request->kategori);
        }
        if ($request->has('jenis') && $request->jenis != 'semua') {
            $query->where('jenis', $request->jenis);
        }
        $pakets = $query->orderBy('created_at', 'desc')->paginate(12);
        $kategoriOptions = Paket::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->filter()
            ->toArray();
        
        $jenisOptions = Paket::select('jenis')
            ->distinct()
            ->orderBy('jenis')
            ->pluck('jenis')
            ->filter()
            ->toArray();
        
        return view('pelanggan.paket', [
            'pakets' => $pakets,
            'kategori' => $kategoriOptions, 
            'jenis' => $jenisOptions,       
            'title' => 'Paket Catering'
        ]);
    }

    public function detailPaket($id)
    {
        $paket = Paket::findOrFail($id);
        $paketLainnya = Paket::where('id', '!=', $id)
            ->inRandomOrder()
            ->limit(4)
            ->get();
            
        return view('pelanggan.detail-paket', [
            'paket' => $paket,
            'paketLainnya' => $paketLainnya,
            'title' => $paket->name_paket
        ]);
    }

    public function profile()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $totalPesanan = Pemesanan::where('id_pelanggan', $pelanggan->id)->count();
        $pesananAktif = Pemesanan::where('id_pelanggan', $pelanggan->id)
            ->whereIn('status_pesan', ['Menunggu Konfirmasi', 'Sedang Diproses', 'Menunggu Kurir'])
            ->count();
        
        return view('pelanggan.profile', [
            'pelanggan' => $pelanggan,
            'totalPesanan' => $totalPesanan,
            'pesananAktif' => $pesananAktif,   
            'title' => 'Profil Saya'
        ]);
    }

    public function editProfile()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        return view('pelanggan.edit-profile', [
            'pelanggan' => $pelanggan,
            'title' => 'Edit Profil'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $request->validate([
            'name_pelanggan' => 'required|string|max:100',
            'email' => 'required|email|unique:pelanggans,email,' . $pelanggan->id,
            'telepon' => 'required|string|max:15',
            'tgl_lahir' => 'nullable|date',
            'alamat1' => 'required|string|max:255',
            'alamat2' => 'nullable|string|max:255',
            'alamat3' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);
        
        $data = $request->only([
            'name_pelanggan', 'email', 'telepon', 'tgl_lahir',
            'alamat1', 'alamat2', 'alamat3'
        ]);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        if ($request->hasFile('foto')) {
            if ($pelanggan->foto && Storage::exists('public/pelanggans/' . $pelanggan->foto)) {
                Storage::delete('public/pelanggans/' . $pelanggan->foto);
            }
            $file = $request->file('foto');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/pelanggans', $filename);
            $data['foto'] = $filename;
        }
        $pelanggan->update($data);
        return redirect('/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}