<?php

namespace Database\Seeders;

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

        // User::factory(25)->create();
        $this->call([
            UserRoleSeeder::class,
        ]);

        $user = User::create([
            'name' => 'Akademik',
            'email' => 'akademik@example.com',
            'password' => bcrypt('12345678'),
        ]);

        $user->roles()->sync([1]);
    }
}
