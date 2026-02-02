<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengirimans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('tgl_kirim')->nullable(); 
            $table->datetime('tgl_tiba')->nullable();
            $table->enum('status_kirim', ['Sedang Dikirim', 'Tiba di Tujuan']); 
            $table->string('bukti_foto', 255)->nullable();
            $table->unsignedBigInteger('id_pesan');
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_pesan')
                  ->references('id')
                  ->on('pemesanans')
                  ->onDelete('cascade');
                  
            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengirimans');
    }
};