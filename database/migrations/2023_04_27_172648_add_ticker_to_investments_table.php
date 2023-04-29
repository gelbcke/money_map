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
        Schema::table('investments', function (Blueprint $table) {
            $table->integer('quantity')->after('date');
            $table->string('ticker')->after('quantity');
            $table->string('invest_group')->after('ticker');
            $table->string('sector')->after('invest_group');
            $table->decimal('buy_price', 10, 2)->after('sector');
            $table->decimal('sell_price', 10, 2)->after('buy_price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('investments', function (Blueprint $table) {
            //
        });
    }
};
