<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('id')
                ->change();
        });

        Schema::table('user_groups', function (Blueprint $table) {
            $table->uuid('owner_id')
                ->change();
        });

        $tables = ['banks', 'budgets', 'expenses', 'incomes', 'investments', 'invoices', 'transfers', 'user_groups', 'wallets'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->uuid('user_id')
                    ->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uuid_on_tables', function (Blueprint $table) {
            //
        });
    }
};
