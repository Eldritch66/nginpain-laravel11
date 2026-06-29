<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use App\Models\Properti;
use App\Models\Sewa;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            PropertiSeeder::class,
        ]);

        $pemilik = User::where('email', 'pemilik@test.com')->first();
        $penyewa = User::where('email', 'penyewa@test.com')->first();

        $properti = Properti::create([
            'pemilik_id' => $pemilik->id,
            'kode_properti' => 'KSN001',
            'nama_properti' => 'Kos Putri Asri',
            'tipe' => 'kost',
            'alamat' => 'Jl. Merdeka No. 123, Bogor',
            'kota' => 'Bogor',
            'harga_per_bulan' => 1500000,
            'harga_per_dua_bulan' => 2500000,
        ]);

        Unit::create([
            'properti_id' => $properti->id,
            'luas_bangunan' => 24.0,
            'jumlah_kamar_tidur' => 1,
            'jumlah_kamar_mandi' => 1,
            'kapasitas_penghuni' => 1,
            'lantai' => 2,
            'keterangan' => 'Kamar mandi dalam, AC, WiFi',
        ]);

        $properti2 = Properti::create([
            'pemilik_id' => $pemilik->id,
            'kode_properti' => 'KNK001',
            'nama_properti' => 'Kontrakan Keluarga',
            'tipe' => 'kontrakan',
            'alamat' => 'Jl. Raya Pajajaran No. 45, Bogor',
            'kota' => 'Bogor',
            'harga_per_bulan' => 3000000,
            'harga_per_dua_bulan' => 5000000,
        ]);

        Unit::create([
            'properti_id' => $properti2->id,
            'luas_bangunan' => 60.0,
            'jumlah_kamar_tidur' => 2,
            'jumlah_kamar_mandi' => 1,
            'kapasitas_penghuni' => 4,
            'lantai' => 1,
            'keterangan' => 'Semi-furnished, listrik token, air PDAM',
        ]);

        $properti3 = Properti::create([
            'pemilik_id' => $pemilik->id,
            'kode_properti' => 'KSN002',
            'nama_properti' => 'Kos Putra Damai',
            'tipe' => 'kost',
            'alamat' => 'Jl. Siliwangi No. 78, Bogor',
            'kota' => 'Bogor',
            'harga_per_bulan' => 1200000,
            'harga_per_dua_bulan' => 2000000,
        ]);

        Unit::create([
            'properti_id' => $properti3->id,
            'luas_bangunan' => 18.0,
            'jumlah_kamar_tidur' => 1,
            'jumlah_kamar_mandi' => 1,
            'kapasitas_penghuni' => 1,
            'lantai' => 3,
            'keterangan' => 'Kamar mandi luar, balkon',
        ]);

        $sewa = Sewa::create([
            'penyewa_id' => $penyewa->id,
            'properti_id' => $properti->id,
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addMonths(2),
            'durasi_bulan' => 2,
            'total_harga' => 2500000,
            'status_sewa' => 'aktif',
            'disetujui_pada' => now(),
        ]);

        Pembayaran::create([
            'sewa_id' => $sewa->id,
            'jumlah' => 2500000,
            'metode' => 'QRIS',
            'status' => 'lunas',
            'periode_bulan' => 2,
            'dibayar_pada' => now(),
        ]);

        $sewa->generateKodeBooking();
    }
}
