<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('users')->count() > 0) {
            return;
        }

        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@moneymap.com',
            'email_verified_at' => now(),
            'password' => Hash::make('secret'),
            'currency_id' => '15',
            'language' => 'pt_BR',
            'timezone_id' => '179',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
