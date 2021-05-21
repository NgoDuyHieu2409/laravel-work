<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWorkTotalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_work_totals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->integer('total_worktime')->nullable();
            $table->integer('total_workcount')->nullable();
            $table->integer('penalty_point')->nullable();
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
        Schema::dropIfExists('user_work_totals');
    }
}
