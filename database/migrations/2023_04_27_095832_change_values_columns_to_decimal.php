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
        Schema::table('expenses', function (Blueprint $table) {
            $table->decimal('value', 10, 2)->change();
            $table->decimal('parcel_vl', 10, 2)->change();
        });

        Schema::table('credit_parcels', function (Blueprint $table) {
            $table->decimal('parcel_vl', 10, 2)->change();
        });

        Schema::table('credit_cards', function (Blueprint $table) {
            $table->decimal('credit_limit', 10, 2)->change();
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->decimal('value', 10, 2)->change();
        });

        Schema::table('investments', function (Blueprint $table) {
            $table->decimal('value', 10, 2)->change();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('value', 10, 2)->change();
        });

        Schema::table('transfers', function (Blueprint $table) {
            $table->decimal('value', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('decimal', function (Blueprint $table) {
            //
        });
    }
};
