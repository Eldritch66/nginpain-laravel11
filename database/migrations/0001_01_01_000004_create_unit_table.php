<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('properti_id')->constrained('properti')->cascadeOnDelete();
            $table->decimal('luas_bangunan', 8, 2)->nullable();
            $table->smallInteger('jumlah_kamar_tidur')->unsigned()->default(1);
            $table->smallInteger('jumlah_kamar_mandi')->unsigned()->default(1);
            $table->smallInteger('kapasitas_penghuni')->unsigned()->default(1);
            $table->smallInteger('lantai')->unsigned()->default(1);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit');
    }
};
