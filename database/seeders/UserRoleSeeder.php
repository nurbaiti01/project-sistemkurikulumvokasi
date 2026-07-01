<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserRole;
class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // [
            //     'name' => 'Superadmin',
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ],
            [
                'name' => 'Akademik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kaprodi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BPM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'WADIR 1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Direktur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        UserRole::insert($data);
    }
}
