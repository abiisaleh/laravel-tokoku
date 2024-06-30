<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@demo.com'
        ]);

        DB::table('categories')->insert([
            ['nama' => 'makanan'],
            ['nama' => 'minuman'],
            ['nama' => 'snack'],
        ]);

        DB::table('settings')->insert([
            ['key' => 'bank', 'value' => 'Mandiri'],
            ['key' => 'rekening', 'value' => '1540016302717'],
        ]);

        $kecamatan = [
            'Jayapura Utara',
            'Jayapura Selatan',
            'Abepura',
            'Muara Tami',
            'Heram',
        ];

        $ongkir = 10000;

        foreach ($kecamatan as $value) {
            DB::table('states')->insert(['kecamatan' => $value, 'ongkir' => $ongkir += 5000]);
        }

        DB::table('products')->insert([
            [
                'nama' => 'Nasi Goreng',
                'category_id' => 1,
                'deskripsi' => 'makanan',
                'harga' => 15000,
                'stok' => 10,
            ],
            [
                'nama' => 'Teh Pucuk',
                'category_id' => 2,
                'deskripsi' => 'minuman',
                'harga' => 5000,
                'stok' => 15,
            ],
            [
                'nama' => 'Roti Coklat',
                'category_id' => 3,
                'deskripsi' => 'snack',
                'harga' => 2000,
                'stok' => 10,
            ],
        ]);
    }
}
