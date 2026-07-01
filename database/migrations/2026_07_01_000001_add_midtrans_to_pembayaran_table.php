<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('metode');
            $table->string('midtrans_transaction_id')->nullable()->after('snap_token');
        });

        DB::statement('ALTER TABLE pembayaran MODIFY metode VARCHAR(50) NULL');
        DB::statement("ALTER TABLE pembayaran MODIFY status ENUM('menunggu', 'lunas', 'ditolak', 'kadaluarsa') DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('snap_token');
            $table->dropColumn('midtrans_transaction_id');
        });

        DB::statement("ALTER TABLE pembayaran MODIFY metode ENUM('QRIS', 'Transfer BCA', 'PayPal') NULL");
        DB::statement("ALTER TABLE pembayaran MODIFY status ENUM('menunggu', 'lunas', 'ditolak', 'kadaluarsa') DEFAULT 'lunas'");
    }
};
