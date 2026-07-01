<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgramStudi;
class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => '11',
                'name' => 'Teknik Pengolahan Sawit',
                'jenjang' => ProgramStudi::JENJANG_D3,
                'singkatan' => ProgramStudi::JENJANG_D3 . '-TPS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '12',
                'name' => 'Perawatan Perbaikan Mesin',
                'jenjang' => ProgramStudi::JENJANG_D3,
                'singkatan' => ProgramStudi::JENJANG_D3 . '-PPM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '13',
                'name' => 'Teknik Informatika',
                'jenjang' => ProgramStudi::JENJANG_D3,
                'singkatan' => ProgramStudi::JENJANG_D3 . '-TIF',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => '14',
                'name' => 'Administrasi Bisnis International',
                'jenjang' => ProgramStudi::JENJANG_D3,
                'singkatan' => ProgramStudi::JENJANG_D3 . '-ABI',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        ProgramStudi::insert($data);
    }
}
