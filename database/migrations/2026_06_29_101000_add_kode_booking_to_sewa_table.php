<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sewa', function (Blueprint $table) {
            $table->string('kode_booking', 15)->unique()->nullable()->after('id');
        });

        $counters = [];

        $sewaList = DB::table('sewa')->orderBy('id')->get();

        foreach ($sewaList as $s) {
            $ym = date('ym', strtotime($s->created_at ?? $s->tanggal_mulai));
            $key = $ym;
            $counters[$key] ??= 1;
            $num = $counters[$key]++;
            DB::table('sewa')
                ->where('id', $s->id)
                ->update(['kode_booking' => 'SW-'.$ym.'-'.str_pad($num, 3, '0', STR_PAD_LEFT)]);
        }
    }

    public function down(): void
    {
        Schema::table('sewa', function (Blueprint $table) {
            $table->dropColumn('kode_booking');
        });
    }
};
