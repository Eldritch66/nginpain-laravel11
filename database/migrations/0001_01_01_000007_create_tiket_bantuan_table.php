<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tiket_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->text('pesan');
            $table->enum('kategori', ['teknis', 'pembayaran', 'properti', 'akun', 'lainnya']);
            $table->enum('status', ['diproses', 'selesai', 'ditutup'])->default('diproses');
            $table->text('balasan_admin')->nullable();
            $table->foreignId('dijawab_oleh')->nullable()->constrained('admin')->nullOnDelete();
            $table->timestamp('dijawab_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket_bantuan');
    }
};
