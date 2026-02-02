<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengirimanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $user = Auth::guard('web')->user();
        
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        $pengirimans = Pengiriman::with(['pemesanan.pelanggan', 'pemesanan.detailPemesanans.paket', 'kurir'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.pengiriman.index', [
            'pengirimans' => $pengirimans,
            'title' => 'Kelola Pengiriman'
        ]);
    }

    public function kurirIndex()
    {
        $user = Auth::guard('web')->user();
        
        if (!$user->isKurir()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        $pengirimans = Pengiriman::with(['pemesanan.pelanggan', 'pemesanan.detailPemesanans.paket'])
            ->where('id_user', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('kurir.pengiriman.index', [
            'pengirimans' => $pengirimans,
            'title' => 'Pengiriman Saya'
        ]);
    }

    public function kurirUpdateStatus(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        $pengiriman = Pengiriman::findOrFail($id);

        if ($user->isKurir() && $pengiriman->id_user != $user->id) {
            return redirect('/kurir/pengiriman')->with('error', 'Akses tidak diizinkan.');
        }

        $request->validate([
            'status_kirim' => 'required|in:Sedang Dikirim,Tiba di Tujuan',
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = [
            'status_kirim' => $request->status_kirim,
            'updated_at' => now(),
        ];

        if ($request->status_kirim == 'Tiba di Tujuan') {
            $data['tgl_tiba'] = now();
            
            $pengiriman->pemesanan->update(['status_pesan' => 'Selesai']);
        }

        if ($request->hasFile('bukti_foto')) {
            if ($pengiriman->bukti_foto) {
                $oldPath = 'public/pengiriman/' . $pengiriman->bukti_foto;
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }
            
            $file = $request->file('bukti_foto');
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $path = $file->storeAs('public/pengiriman', $filename);
            $data['bukti_foto'] = $filename;
        }

        $pengiriman->update($data);

        return redirect()->route('kurir.pengiriman')->with('success', 'Status pengiriman berhasil diperbarui!');
    }

    public function create()
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        $pemesanans = Pemesanan::where('status_pesan', 'Menunggu Kurir')->get();
        $kurirs = User::where('level', 'kurir')->get();
        
        return view('admin.pengiriman.create', [
            'pemesanans' => $pemesanans,
            'kurirs' => $kurirs,
            'title' => 'Tambah Pengiriman'
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        $request->validate([
            'id_pesan' => 'required|exists:pemesanans,id',
            'id_user' => 'required|exists:users,id',
            'status_kirim' => 'required|in:Sedang Dikirim,Tiba di Tujuan',
        ]);

        $pemesanan = Pemesanan::findOrFail($request->id_pesan);
        $existingPengiriman = Pengiriman::where('id_pesan', $request->id_pesan)->first();
        
        if ($existingPengiriman) {
            $existingPengiriman->update([
                'id_user' => $request->id_user,
                'status_kirim' => $request->status_kirim,
                'tgl_kirim' => now(),
                'updated_at' => now(),
            ]);
            $pengiriman = $existingPengiriman;
        } else {
            if (!$request->id_user) {
                return back()->with('error', 'Pilih kurir terlebih dahulu!');
            }
            $pengiriman = Pengiriman::create([
                'id_pesan' => $request->id_pesan,
                'id_user' => $request->id_user, 
                'status_kirim' => $request->status_kirim,
                'tgl_kirim' => now(),
                'tgl_tiba' => null,
            ]);
        }

        $pemesanan->update(['status_pesan' => 'Menunggu Kurir']);

        return redirect('/admin/pengiriman')->with('success', 'Pengiriman berhasil dibuat.');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        $pengiriman = Pengiriman::findOrFail($id);

        if ($user->isKurir() && $pengiriman->id_user != $user->id) {
            return redirect('/kurir/pengiriman')->with('error', 'Akses tidak diizinkan.');
        }

        $request->validate([
            'status_kirim' => 'required|in:Sedang Dikirim,Tiba di Tujuan',
            'bukti_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = [
            'status_kirim' => $request->status_kirim,
            'updated_at' => now(),
        ];

        if ($request->status_kirim == 'Tiba di Tujuan') {
            $data['tgl_tiba'] = now();
            
            $pengiriman->pemesanan->update(['status_pesan' => 'Selesai']);
        }

        if ($request->hasFile('bukti_foto')) {
            if ($pengiriman->bukti_foto) {
                $oldPath = 'public/pengiriman/' . $pengiriman->bukti_foto;
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }
            
            $file = $request->file('bukti_foto');
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $file->storeAs('public/pengiriman', $filename);
            $data['bukti_foto'] = $filename;
        }

        $pengiriman->update($data);

        return back()->with('success', 'Status pengiriman berhasil diperbarui!');
    }

    public function show($id)
    {
        $user = Auth::guard('web')->user();
        $pengiriman = Pengiriman::with(['pemesanan.pelanggan', 'pemesanan.detailPemesanans.paket', 'kurir'])->findOrFail($id);

        if ($user->isKurir() && $pengiriman->id_user != $user->id) {
            return redirect('/kurir/pengiriman')->with('error', 'Akses tidak diizinkan.');
        }
        
        $view = $user->isKurir() ? 'kurir.pengiriman.show' : 'admin.pengiriman.show';
        
        return view($view, [
            'pengiriman' => $pengiriman,
            'title' => 'Detail Pengiriman'
        ]);
    }

    public function assignKurir(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        $request->validate([
            'id_user' => 'required|exists:users,id',
        ]);

        $pengiriman = Pengiriman::findOrFail($id);
        $pengiriman->update([
            'id_user' => $request->id_user,
            'status_kirim' => 'Sedang Dikirim',
            'tgl_kirim' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Kurir berhasil ditugaskan.');
    }

    public function uploadBukti(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        
        if (!$user || !$user->isKurir()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }
        
        $pengiriman = Pengiriman::where('id', $id)
            ->where('id_user', $user->id)
            ->firstOrFail();
        
        $request->validate([
            'bukti_foto' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        
        if ($request->hasFile('bukti_foto')) {
            if ($pengiriman->bukti_foto) {
                $oldPath = 'public/pengiriman/' . $pengiriman->bukti_foto;
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }
            $file = $request->file('bukti_foto');
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $file->storeAs('public/pengiriman', $filename);
            
            $pengiriman->update([
                'bukti_foto' => $filename,
                'updated_at' => now(),
            ]);
        }
        
        return back()->with('success', 'Bukti pengiriman berhasil diupload.');
    }
}