<?php

namespace Database\Seeders;

use App\Models\FotoProperti;
use App\Models\Properti;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertiSeeder extends Seeder
{
    public function run(): void
    {
        $pemilik = User::where('email', 'kobul@gmail.com')->first();

        $properti1 = Properti::create([
            'pemilik_id' => $pemilik->id,
            'kode_properti' => 'KSN003',
            'nama_properti' => 'Kontrakan Kobul 001',
            'tipe' => 'kost',
            'alamat' => 'Jl. H. Ahmad Yunus No.13, Sukaresmi, Tanah Sareal',
            'kota' => 'Bogor, Jawa Barat',
            'harga_per_bulan' => 400000,
            'harga_per_dua_bulan' => 800000,
        ]);

        Unit::create([
            'properti_id' => $properti1->id,
            'luas_bangunan' => 40.00,
            'jumlah_kamar_tidur' => 2,
            'jumlah_kamar_mandi' => 1,
            'kapasitas_penghuni' => 1,
            'lantai' => 1,
        ]);

        FotoProperti::create([
            'properti_id' => $properti1->id,
            'url' => '/storage/foto_properti/NRkbjhsm9ZebEHHK2epaJssUIaAjjNC0YuxAwYEO.jpg',
        ]);

        $properti2 = Properti::create([
            'pemilik_id' => $pemilik->id,
            'kode_properti' => 'KNK002',
            'nama_properti' => 'Kontrakan Kobul 002',
            'tipe' => 'kontrakan',
            'alamat' => 'Jl. H. Ahmad Yunus No.13, Sukaresmi, Tanah Sareal',
            'kota' => 'Bogor, Jawa Barat',
            'harga_per_bulan' => 400000,
            'harga_per_dua_bulan' => 800000,
        ]);

        Unit::create([
            'properti_id' => $properti2->id,
            'luas_bangunan' => 50.00,
            'jumlah_kamar_tidur' => 2,
            'jumlah_kamar_mandi' => 2,
            'kapasitas_penghuni' => 2,
            'lantai' => 1,
        ]);

        FotoProperti::create([
            'properti_id' => $properti2->id,
            'url' => '/storage/foto_properti/TXRL4g4bCITyCWDsZHHUoQcrDoMO7hYPkAwxeuMt.jpg',
        ]);

        $properti3 = Properti::create([
            'pemilik_id' => $pemilik->id,
            'kode_properti' => 'KSN004',
            'nama_properti' => 'Kontrakan Kobul 003',
            'tipe' => 'kost',
            'alamat' => 'Jl. Pancasan Baru No.92, Pasir Jaya, Kec. Bogor Barat.',
            'kota' => 'Bogor, Jawa Barat',
            'harga_per_bulan' => 300000,
            'harga_per_dua_bulan' => 600000,
        ]);

        Unit::create([
            'properti_id' => $properti3->id,
            'luas_bangunan' => 40.00,
            'jumlah_kamar_tidur' => 1,
            'jumlah_kamar_mandi' => 1,
            'kapasitas_penghuni' => 1,
            'lantai' => 1,
        ]);

        FotoProperti::create([
            'properti_id' => $properti3->id,
            'url' => '/storage/foto_properti/AK4yaoXodYsrL0pFpQGKtVtTLZYWMwGN2vVtWOO4.jpg',
        ]);
    }
}
