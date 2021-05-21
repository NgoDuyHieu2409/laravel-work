<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_skill', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('worker_id')->unsigned()->index('worker_skill_worker_id_foreign');
			$table->bigInteger('skill_id')->unsigned()->index('worker_skill_skill_id_foreign');
			$table->bigInteger('user_id')->unsigned()->index('worker_skill_home_id_foreign');
            $table->integer('work_id');
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
        Schema::dropIfExists('worker_skill');
    }
}
