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
        //initialize roles
        $this->call(RolesAndPermissionsSeeder::class);

        //default super-admin user
        User::create([
            'name' => 'super-admin',
            'email' => 'ehkhu.thamaing@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('super-admin');

        // User::factory(3)->create();
    }
}
