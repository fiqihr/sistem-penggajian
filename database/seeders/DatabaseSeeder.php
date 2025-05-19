<?php

namespace Database\Seeders;

use App\Models\PotonganGaji;
use App\Models\Tunjangan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@mail.com',
            'password' => 'password',
            'hak_akses' => 'admin'
        ]);

        PotonganGaji::factory()->createMany([
            [
                'nama_potongan' => 'Tidak Hadir',
                'jml_potongan' => 0,
            ],
            [
                'nama_potongan' => 'Sakit',
                'jml_potongan' => 0,
            ],
            [
                'nama_potongan' => 'BPR',
                'jml_potongan' => 0,
            ],
            [
                'nama_potongan' => 'Lazisnu',
                'jml_potongan' => 0,
            ]
        ]);

        Tunjangan::factory()->createMany([
            [
                'nama_tunjangan' => 'Kepala Sekolah',
                'jml_tunjangan' => 750000,
            ],
            [
                'nama_tunjangan' => 'Wali Kelas',
                'jml_tunjangan' => 200000,
            ],
            [
                'nama_tunjangan' => 'Wakil Kepala Sekolah',
                'jml_tunjangan' => 500000,
            ],
            [
                'nama_tunjangan' => 'Kepala Program',
                'jml_tunjangan' => 450000,
            ],
            [
                'nama_tunjangan' => 'Pembina Eskul',
                'jml_tunjangan' => 150000,
            ],
        ]);
    }
}