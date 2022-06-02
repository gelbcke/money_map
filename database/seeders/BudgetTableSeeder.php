<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        if (DB::table('budgets')->count() > 0) {
            return;
        }

        DB::table('budgets')->insert([
            [
                'user_id' => '1',
                'name' => 'Essential',
                'budget' => '50',
                'operation' => 'OUT',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => '1',
                'name' => 'Leisure',
                'budget' => '35',
                'operation' => 'OUT',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => '1',
                'name' => 'Investment',
                'budget' => '15',
                'operation' => 'SAVE',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
