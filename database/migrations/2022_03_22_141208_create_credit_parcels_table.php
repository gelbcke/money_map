<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditParcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_parcels', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_id');
            $table->integer('expense_id');
            $table->date('date');
            $table->integer('parcel_nb');
            $table->double('parcel_vl', 10,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_parcels');
    }
}
