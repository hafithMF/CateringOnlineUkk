<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pakets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_paket', 50);
            $table->enum('jenis', ['Prasmanan', 'Box']);
            $table->enum('kategori', ['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studi Tour', 'Rapat']);
            $table->integer('jumlah_pax');
            $table->integer('harga_paket');
            $table->text('deskripsi');
            $table->string('foto1', 255)->nullable();
            $table->string('foto2', 255)->nullable();
            $table->string('foto3', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};