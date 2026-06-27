<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sewa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyewa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('properti_id')->nullable()->constrained('properti')->nullOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->smallInteger('durasi_bulan')->unsigned();
            $table->decimal('total_harga', 15, 2);
            $table->enum('status_sewa', ['aktif', 'pending', 'dibatalkan'])->default('aktif');
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE sewa ADD CONSTRAINT chk_durasi CHECK (durasi_bulan >= 2)');
    }

    public function down(): void
    {
        Schema::dropIfExists('sewa');
    }
};
