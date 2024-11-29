<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Leonardo Rodriguez',
            'email' => 'leo.p@tomate.com.co ',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Florentino Perez',
            'email' => 'florentino-perez@example.com',
            'password' => Hash::make('password456'),
        ]);

        User::create([
            'name' => 'Carlos Ruiz',
            'email' => 'carlos.ruiz@example.com',
            'password' => Hash::make('password789'),
        ]);
    }
}
