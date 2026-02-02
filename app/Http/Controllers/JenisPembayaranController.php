<?php

namespace App\Http\Controllers;

use App\Models\JenisPembayaran;
use App\Models\DetailJenisPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JenisPembayaranController extends Controller
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

        $jenisPembayarans = JenisPembayaran::with(['detailJenisPembayarans', 'pemesanans'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.jenis-pembayaran.index', [
            'jenisPembayarans' => $jenisPembayarans,
            'title' => 'Kelola Metode Pembayaran'
        ]);
    }

    public function create()
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        return view('admin.jenis-pembayaran.create', [
            'title' => 'Tambah Metode Pembayaran'
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        $request->validate([
            'metode_pembayaran' => 'required|string|max:30|unique:jenis_pembayarans,metode_pembayaran',
            'no_rek' => 'nullable|string|max:25',
            'tempat_bayar' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $jenisPembayaran = JenisPembayaran::create([
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        if ($request->filled('no_rek') || $request->filled('tempat_bayar') || $request->hasFile('logo')) {
            $dataDetail = [
                'id_jenis_pembayaran' => $jenisPembayaran->id,
                'no_rek' => $request->no_rek ?? null,
                'tempat_bayar' => $request->tempat_bayar ?? null,
            ];

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/jenis-pembayaran', $filename);
                $dataDetail['logo'] = $filename;
            }

            DetailJenisPembayaran::create($dataDetail);
        }

        return redirect('/admin/jenis-pembayaran')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        $jenisPembayaran = JenisPembayaran::with('detailJenisPembayarans')->findOrFail($id);
        
        return view('admin.jenis-pembayaran.edit', [
            'jenisPembayaran' => $jenisPembayaran,
            'title' => 'Edit Metode Pembayaran'
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        $jenisPembayaran = JenisPembayaran::findOrFail($id);

        $request->validate([
            'metode_pembayaran' => 'required|string|max:30|unique:jenis_pembayarans,metode_pembayaran,' . $jenisPembayaran->id,
            'no_rek' => 'nullable|string|max:25',
            'tempat_bayar' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $jenisPembayaran->update([
            'metode_pembayaran' => $request->metode_pembayaran,
        ]);

        $detail = DetailJenisPembayaran::where('id_jenis_pembayaran', $jenisPembayaran->id)->first();

        $dataDetail = [
            'no_rek' => $request->no_rek ?? null,
            'tempat_bayar' => $request->tempat_bayar ?? null,
        ];

        if ($request->hasFile('logo')) {
            // Delete old file if exists
            if ($detail && $detail->logo) {
                Storage::delete('public/jenis-pembayaran/' . $detail->logo);
            }
            
            $file = $request->file('logo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/jenis-pembayaran', $filename);
            $dataDetail['logo'] = $filename;
        }

        if ($detail) {
            $detail->update($dataDetail);
        } elseif ($request->filled('no_rek') || $request->filled('tempat_bayar') || $request->hasFile('logo')) {
            $dataDetail['id_jenis_pembayaran'] = $jenisPembayaran->id;
            DetailJenisPembayaran::create($dataDetail);
        }

        return redirect('/admin/jenis-pembayaran')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }

        $jenisPembayaran = JenisPembayaran::findOrFail($id);
        
        $details = DetailJenisPembayaran::where('id_jenis_pembayaran', $jenisPembayaran->id)->get();
        foreach ($details as $detail) {
            if ($detail->logo) {
                Storage::delete('public/jenis-pembayaran/' . $detail->logo);
            }
            $detail->delete();
        }
        
        $jenisPembayaran->delete();

        return redirect('/admin/jenis-pembayaran')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}