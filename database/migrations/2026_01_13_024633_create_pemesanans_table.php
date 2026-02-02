<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_jenis_bayar');
            $table->string('no_resi', 30)->nullable();
            $table->datetime('tgl_pesan');
            $table->enum('status_pesan', ['Menunggu Konfirmasi', 'Sedang Diproses', 'Menunggu Kurir', 'Selesai', 'Dibatalkan']);
            $table->bigInteger('total_bayar');
            $table->timestamps();

            $table->foreign('id_pelanggan')
                  ->references('id')
                  ->on('pelanggans')
                  ->onDelete('cascade');
                  
            $table->foreign('id_jenis_bayar')
                  ->references('id')
                  ->on('jenis_pembayarans')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};