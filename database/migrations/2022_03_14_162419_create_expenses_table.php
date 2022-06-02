<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id')->nullable();
            $table->integer('user_id');
            $table->integer('budget_id');
            $table->date('date');
            $table->double('value', 10,2);
            $table->integer('bank_id');
            $table->integer('parcels')->nullable();
            $table->double('parcel_vl', 10,2)->nullable();
            $table->date('end_parcels')->nullable();
            $table->string('details')->nullable();
            $table->integer('rec_expense')->nullable();
            $table->integer('payment_method');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
