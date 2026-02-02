<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_jenis_pembayarans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_jenis_pembayaran');
            $table->string('no_rek', 25);
            $table->string('tempat_bayar', 50);
            $table->string('logo', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_jenis_pembayaran')
                  ->references('id')
                  ->on('jenis_pembayarans')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_jenis_pembayarans');
    }
};