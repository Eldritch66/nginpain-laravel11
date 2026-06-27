<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemilik_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_properti');
            $table->enum('tipe', ['kost', 'kontrakan']);
            $table->text('alamat');
            $table->string('kota');
            $table->integer('harga_per_bulan')->nullable();
            $table->integer('harga_per_dua_bulan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properti');
    }
};
