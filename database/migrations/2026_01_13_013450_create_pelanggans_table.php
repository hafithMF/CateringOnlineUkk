<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_pelanggan', 100);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->date('tgl_lahir')->nullable();
            $table->string('telepon', 15)->nullable();
            $table->string('alamat1', 255)->nullable();
            $table->string('alamat2', 255)->nullable();
            $table->string('alamat3', 255)->nullable();
            $table->string('kartu_id', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggans');
    }
};