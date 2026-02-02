<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Paket;
use App\Models\JenisPembayaran;
use App\Models\DetailPemesanan;
use App\Models\Pelanggan;
use App\Models\Pengiriman; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PemesananController extends Controller
{
    public function create($paket_id)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $paket = Paket::findOrFail($paket_id);
        $jenisPembayarans = JenisPembayaran::with('detailJenisPembayarans')->get();
        $minDate = date('Y-m-d', strtotime('+1 day'));
        
        Log::info('Pesan form loaded:', [
            'pelanggan_id' => $pelanggan->id,
            'paket_id' => $paket_id,
            'minDate' => $minDate
        ]);
        
        return view('pelanggan.pesan', [
            'paket' => $paket,
            'jenisPembayarans' => $jenisPembayarans,
            'pelanggan' => $pelanggan,
            'minDate' => $minDate,
            'title' => 'Pesan ' . $paket->name_paket
        ]);
    }

    public function store(Request $request)
    {
        Log::info('========= PEMESANAN STORE START =========');
        Log::info('Request Data:', $request->all());
        
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validator = \Validator::make($request->all(), [
            'paket_id' => 'required|exists:pakets,id',
            'id_jenis_bayar' => 'required|exists:jenis_pembayarans,id',
            'jumlah_pax' => 'required|integer|min:1',
            'tgl_pesan' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $paket = Paket::findOrFail($request->paket_id);
            if ($request->jumlah_pax < $paket->jumlah_pax) {
                return back()
                    ->withErrors(['jumlah_pax' => 'Jumlah pax minimal ' . $paket->jumlah_pax])
                    ->withInput();
            }
            $minDate = date('Y-m-d', strtotime('+1 day'));
            if ($request->tgl_pesan < $minDate) {
                return back()
                    ->withErrors(['tgl_pesan' => 'Tanggal acara minimal besok'])
                    ->withInput();
            }
            do {
                $no_resi = 'CTR-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            } while (Pemesanan::where('no_resi', $no_resi)->exists());
            $total_bayar = $paket->harga_paket * $request->jumlah_pax;
            $pemesanan = Pemesanan::create([
                'id_pelanggan' => $pelanggan->id,
                'id_jenis_bayar' => $request->id_jenis_bayar,
                'no_resi' => $no_resi,
                'tgl_pesan' => $request->tgl_pesan,
                'status_pesan' => 'Menunggu Konfirmasi',
                'total_bayar' => $total_bayar,
            ]);
            DetailPemesanan::create([
                'id_pemesanan' => $pemesanan->id,
                'id_paket' => $paket->id,
                'subtotal' => $total_bayar, 
            ]);
            
            DB::commit();
            
            return redirect()->route('pesanan-saya')
                ->with([
                    'success' => '🎉 Pesanan berhasil dibuat! Nomor Resi: ' . $no_resi,
                    'resi' => $no_resi
                ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Pemesanan Error: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function index()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        Log::info('Loading pesanan untuk pelanggan:', ['id' => $pelanggan->id]);
        
        $pemesanans = Pemesanan::with(['jenisPembayaran', 'pengiriman', 'detailPemesanans.paket'])
            ->where('id_pelanggan', $pelanggan->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        Log::info('Pemesanans found:', ['count' => $pemesanans->count()]);
            
        return view('pelanggan.pesanan-saya', [
            'pemesanans' => $pemesanans,
            'title' => 'Pesanan Saya'
        ]);
    }

    public function show($id)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $pemesanan = Pemesanan::with([
                'jenisPembayaran.detailJenisPembayarans', 
                'pengiriman.kurir',
                'detailPemesanans.paket',
                'pelanggan'
            ])
            ->where('id', $id)
            ->where('id_pelanggan', $pelanggan->id)
            ->firstOrFail();
            
        return view('pelanggan.detail-pesanan', [
            'pemesanan' => $pemesanan,
            'title' => 'Detail Pesanan #' . $pemesanan->no_resi
        ]);
    }

    public function cancel($id)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $pemesanan = Pemesanan::where('id', $id)
            ->where('id_pelanggan', $pelanggan->id)
            ->firstOrFail();
            
        if ($pemesanan->status_pesan !== 'Menunggu Konfirmasi') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
        }
        
        $pemesanan->update(['status_pesan' => 'Dibatalkan']);
        
        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function adminIndex()
    {
        $user = Auth::guard('web')->user();
        
        if (!$user || (!$user->isAdmin() && !$user->isOwner())) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }
        
        $status = request('status');
        $date = request('date');
        
        $pemesanans = Pemesanan::with(['pelanggan', 'jenisPembayaran', 'detailPemesanans.paket'])
            ->when($status, function ($query, $status) {
                return $query->where('status_pesan', $status);
            })
            ->when($date, function ($query, $date) {
                return $query->whereDate('tgl_pesan', $date);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.pemesanan.index', [
            'pemesanans' => $pemesanans,
            'title' => 'Kelola Pemesanan'
        ]);
    }

    public function adminShow($id)
    {
        $user = Auth::guard('web')->user();
        
        if (!$user || (!$user->isAdmin() && !$user->isOwner())) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }
        
        $pemesanan = Pemesanan::with([
                'pelanggan', 
                'jenisPembayaran.detailJenisPembayarans', 
                'pengiriman.kurir',
                'detailPemesanans.paket'
            ])
            ->findOrFail($id);
            
        return view('admin.pemesanan.show', [
            'pemesanan' => $pemesanan,
            'title' => 'Detail Pemesanan #' . $pemesanan->no_resi
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::guard('web')->user();
        
        if (!$user || (!$user->isAdmin() && !$user->isOwner())) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'status' => 'required|in:Menunggu Konfirmasi,Menunggu Kurir,Selesai,Dibatalkan'
        ]);
        
        $pemesanan = Pemesanan::findOrFail($id);
        $oldStatus = $pemesanan->status_pesan;
        $pemesanan->update(['status_pesan' => $request->status]);
        
        if ($oldStatus != 'Menunggu Kurir' && $request->status == 'Menunggu Kurir') {
            $existingPengiriman = Pengiriman::where('id_pesan', $pemesanan->id)->first();
            
            if (!$existingPengiriman) {
                $availableKurir = \App\Models\User::where('level', 'kurir')->first();
                Pengiriman::create([
                    'id_pesan' => $pemesanan->id,
                    'id_user' => $availableKurir ? $availableKurir->id : null, 
                    'status_kirim' => 'Sedang Dikirim',
                    'tgl_kirim' => null,
                    'tgl_tiba' => null,
                ]);
            }
        }

        if ($request->status == 'Selesai' && $pemesanan->pengiriman) {
            $pemesanan->pengiriman->update([
                'status_kirim' => 'Tiba di Tujuan',
                'tgl_tiba' => now(),
            ]);
        }
        
        return back()->with('success', 'Status pemesanan berhasil diperbarui.');
    }
    
    public function adminCancel($id)
    {
        $user = Auth::guard('web')->user();
        
        if (!$user || (!$user->isAdmin() && !$user->isOwner())) {
            return redirect('/dashboard')->with('error', 'Akses tidak diizinkan.');
        }
        
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->update(['status_pesan' => 'Dibatalkan']);
        if ($pemesanan->pengiriman) {
            $pemesanan->pengiriman->update(['status_kirim' => 'Dibatalkan']);
        }
        return back()->with('success', 'Pemesanan berhasil dibatalkan.');
    }

    public function checkPemesanan($paket_id)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        
        if (!$pelanggan) {
            return response()->json(['error' => 'Silakan login terlebih dahulu.'], 401);
        }
        
        $paket = Paket::find($paket_id);
        
        if (!$paket) {
            return response()->json(['error' => 'Paket tidak ditemukan.'], 404);
        }
        
        return response()->json([
            'success' => true,
            'paket' => [
                'id' => $paket->id,
                'name' => $paket->name_paket,
                'harga' => $paket->harga_paket,
                'min_pax' => $paket->jumlah_pax,
            ],
            'pelanggan' => [
                'id' => $pelanggan->id,
                'name' => $pelanggan->name_pelanggan,
                'alamat' => $pelanggan->alamat1,
            ]
        ]);
    }
}