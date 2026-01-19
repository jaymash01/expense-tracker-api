<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Joseph Mashauri',
                'email' => 'jaymash01@gmail.com',
                'password' => Hash::make('123456'),
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'id' => 2,
                'name' => 'Tyler Robinson',
                'email' => 'tyler.robinson@gmail.com',
                'password' => Hash::make('123456'),
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'id' => 3,
                'name' => 'Lisa Thompson',
                'email' => 'lisath@gmail.com',
                'password' => Hash::make('123456'),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
        ]);
    }
}
