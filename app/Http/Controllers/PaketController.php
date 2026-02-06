<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index()
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $pakets = Paket::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.paket.index', [
            'pakets' => $pakets,
            'title' => 'Kelola Paket'
        ]);
    }

    public function create()
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        return view('admin.paket.create', [
            'title' => 'Tambah Paket'
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        Log::info('Paket Store Request:', $request->all());

        $request->validate([
            'name_paket' => 'required|string|max:50',
            'jenis' => 'required|in:Prasmanan,Box',
            'kategori' => 'required|in:Pernikahan,Selamatan,Ulang Tahun,Studi Tour,Rapat',
            'jumlah_pax' => 'required|integer|min:1',
            'harga_paket' => 'required|numeric|min:1000',
            'deskripsi' => 'required|string',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = $request->only([
            'name_paket', 'jenis', 'kategori', 'jumlah_pax', 'deskripsi'
        ]);

        $data['harga_paket'] = (int) $request->input('harga_paket');

        Log::info('Data setelah cleaning harga:', $data);

        foreach (['foto1', 'foto2', 'foto3'] as $fotoField) {
            if ($request->hasFile($fotoField)) {
                $file = $request->file($fotoField);
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Simpan di public/pakets/
                $publicPath = 'pakets';
                $file->move(public_path($publicPath), $filename);
                $data[$fotoField] = $publicPath . '/' . $filename;
                
                Log::info("File {$fotoField} uploaded:", ['path' => $data[$fotoField]]);
            } else {
                Log::info("File {$fotoField} not uploaded");
            }
        }

        try {
            $paket = Paket::create($data);
            Log::info('Paket created successfully:', ['id' => $paket->id]);
            
            return redirect('/admin/paket')->with('success', 'Paket berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error creating paket:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal menambahkan paket: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $paket = Paket::findOrFail($id);
        
        return view('admin.paket.edit', [
            'paket' => $paket,
            'title' => 'Edit Paket'
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $paket = Paket::findOrFail($id);

        $request->validate([
            'name_paket' => 'required|string|max:50',
            'jenis' => 'required|in:Prasmanan,Box',
            'kategori' => 'required|in:Pernikahan,Selamatan,Ulang Tahun,Studi Tour,Rapat',
            'jumlah_pax' => 'required|integer|min:1',
            'harga_paket' => 'required|numeric|min:1000',
            'deskripsi' => 'required|string',
            'foto1' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'foto2' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'foto3' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = $request->only([
            'name_paket', 'jenis', 'kategori', 'jumlah_pax', 'deskripsi'
        ]);

        $data['harga_paket'] = (int) $request->input('harga_paket');

        foreach (['foto1', 'foto2', 'foto3'] as $fotoField) {
            if ($request->hasFile($fotoField)) {
                // Hapus file lama jika ada
                if ($paket->$fotoField && file_exists(public_path($paket->$fotoField))) {
                    unlink(public_path($paket->$fotoField));
                }
                
                $file = $request->file($fotoField);
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Simpan di public/pakets/
                $publicPath = 'pakets';
                $file->move(public_path($publicPath), $filename);
                $data[$fotoField] = $publicPath . '/' . $filename;
            }
        }

        $paket->update($data);

        return redirect('/admin/paket')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy($id)
    { 
        $user = Auth::guard('web')->user();
        if (!$user->isAdmin() && !$user->isOwner()) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $paket = Paket::findOrFail($id);
        
        // Hapus file gambar
        foreach (['foto1', 'foto2', 'foto3'] as $foto) {
            if ($paket->$foto && file_exists(public_path($paket->$foto))) {
                unlink(public_path($paket->$foto));
            }
        }
        
        $paket->delete();

        return redirect('/admin/paket')->with('success', 'Paket berhasil dihapus.');
    }

    public function indexApi()
    {
        $pakets = Paket::all();
        return response()->json([
            'success' => true,
            'data' => $pakets
        ]);
    }

    public function showApi($id)
    {
        $paket = Paket::find($id);
        
        if (!$paket) {
            return response()->json([
                'success' => false,
                'message' => 'Paket tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $paket
        ]);
    }
}