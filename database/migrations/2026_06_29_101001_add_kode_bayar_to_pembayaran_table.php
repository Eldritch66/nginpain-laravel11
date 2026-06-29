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
            $table->string('kode_bayar', 15)->unique()->nullable()->after('id');
        });

        $methodMap = [
            'QRIS' => 'QRS',
            'Transfer BCA' => 'BCA',
            'PayPal' => 'PYP',
        ];

        $counters = [];

        $pembayaranList = DB::table('pembayaran')->orderBy('id')->get();

        foreach ($pembayaranList as $p) {
            $code = $methodMap[$p->metode] ?? 'XXX';
            $counters[$code] ??= 1;
            $num = $counters[$code]++;
            DB::table('pembayaran')
                ->where('id', $p->id)
                ->update(['kode_bayar' => 'PAY-'.$code.'-'.str_pad($num, 3, '0', STR_PAD_LEFT)]);
        }
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('kode_bayar');
        });
    }
};
