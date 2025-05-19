<?php

namespace Database\Seeders;

use App\Models\PotonganGaji;
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
    }
}
