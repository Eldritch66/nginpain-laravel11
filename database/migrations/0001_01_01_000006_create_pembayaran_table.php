<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sewa_id')->constrained('sewa')->cascadeOnDelete();
            $table->decimal('jumlah', 15, 2);
            $table->enum('metode', ['QRIS', 'Transfer BCA', 'PayPal'])->nullable();
            $table->enum('status', ['menunggu', 'lunas', 'ditolak', 'kadaluarsa'])->default('lunas');
            $table->smallInteger('periode_bulan')->unsigned()->nullable();
            $table->timestamp('dibayar_pada')->nullable();
            $table->timestamps();
        });

        DB::statement('ALTER TABLE pembayaran ADD CONSTRAINT chk_jumlah CHECK (jumlah > 0)');
        DB::statement('ALTER TABLE pembayaran ADD CONSTRAINT chk_periode_bulan CHECK (periode_bulan >= 1)');
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
