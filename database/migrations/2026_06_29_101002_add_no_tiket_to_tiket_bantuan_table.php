<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiket_bantuan', function (Blueprint $table) {
            $table->string('no_tiket', 15)->unique()->nullable()->after('id');
        });

        $kategoriMap = [
            'teknis' => 'TEK',
            'pembayaran' => 'PAY',
            'properti' => 'PRP',
            'akun' => 'AKN',
            'lainnya' => 'LNY',
        ];

        $counters = [];

        $tiketList = DB::table('tiket_bantuan')->orderBy('id')->get();

        foreach ($tiketList as $t) {
            $code = $kategoriMap[$t->kategori] ?? 'XXX';
            $counters[$code] ??= 1;
            $num = $counters[$code]++;
            DB::table('tiket_bantuan')
                ->where('id', $t->id)
                ->update(['no_tiket' => 'TKT-'.$code.'-'.str_pad($num, 3, '0', STR_PAD_LEFT)]);
        }
    }

    public function down(): void
    {
        Schema::table('tiket_bantuan', function (Blueprint $table) {
            $table->dropColumn('no_tiket');
        });
    }
};
