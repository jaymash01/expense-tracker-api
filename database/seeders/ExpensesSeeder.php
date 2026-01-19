<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('expenses')->insert([
            [
                'id' => 1,
                'user_id' => 1,
                'category_id' => 1,
                'amount' => 5000,
                'description' => 'Maji',
                'expense_date' => Carbon::today()->subDays(9),
                'created_at' => Carbon::now()->subDays(9)->addMinutes(5),
                'updated_at' => Carbon::now()->subDays(9)->addMinutes(5),
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'category_id' => 2,
                'amount' => 2000,
                'description' => 'Bodaboda',
                'expense_date' => Carbon::today()->subDays(8),
                'created_at' => Carbon::now()->subDays(8)->addMinutes(12),
                'updated_at' => Carbon::now()->subDays(8)->addMinutes(12),
            ],
            [
                'id' => 3,
                'user_id' => 2,
                'category_id' => 3,
                'amount' => 30000,
                'description' => 'Vyakula',
                'expense_date' => Carbon::today()->subDays(5),
                'created_at' => Carbon::now()->subDays(5)->addMinutes(7),
                'updated_at' => Carbon::now()->subDays(5)->addMinutes(7),
            ],
            [
                'id' => 4,
                'user_id' => 3,
                'category_id' => 4,
                'amount' => 15000,
                'description' => 'Umeme',
                'expense_date' => Carbon::today()->subDays(1),
                'created_at' => Carbon::now()->subDays(1)->addMinutes(25),
                'updated_at' => Carbon::now()->subDays(1)->addMinutes(25),
            ],
        ]);
    }
}
