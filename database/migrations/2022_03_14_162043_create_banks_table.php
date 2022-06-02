<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id')->nullable();
            $table->integer('user_id');
            $table->integer('wallet_id');
            $table->integer('status')->nullable();
            $table->string('name');
            $table->integer('f_deb')->nullable();
            $table->integer('f_cred')->nullable();
            $table->integer('f_invest')->nullable();
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
        Schema::dropIfExists('banks');
    }
}
