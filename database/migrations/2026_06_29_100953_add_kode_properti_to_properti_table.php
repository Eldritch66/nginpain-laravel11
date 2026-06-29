<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properti', function (Blueprint $table) {
            $table->string('kode_properti', 10)->unique()->nullable()->after('id');
        });

        $counters = ['kost' => 1, 'kontrakan' => 1];

        $propertiList = DB::table('properti')->orderBy('id')->get();

        foreach ($propertiList as $p) {
            $prefix = $p->tipe === 'kost' ? 'KSN' : 'KNK';
            $num = $counters[$p->tipe]++;
            DB::table('properti')
                ->where('id', $p->id)
                ->update(['kode_properti' => $prefix.str_pad($num, 3, '0', STR_PAD_LEFT)]);
        }
    }

    public function down(): void
    {
        Schema::table('properti', function (Blueprint $table) {
            $table->dropColumn('kode_properti');
        });
    }
};
