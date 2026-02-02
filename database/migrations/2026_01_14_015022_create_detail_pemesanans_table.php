<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pemesanans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_pemesanan');
            $table->unsignedBigInteger('id_paket');
            $table->bigInteger('subtotal');  
            $table->timestamps();

            $table->foreign('id_pemesanan')
                  ->references('id')
                  ->on('pemesanans')
                  ->onDelete('cascade');
                  
            $table->foreign('id_paket')
                  ->references('id')
                  ->on('pakets')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pemesanans');
    }
};