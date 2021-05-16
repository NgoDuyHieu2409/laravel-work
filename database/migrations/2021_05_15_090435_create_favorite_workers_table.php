<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_workers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('worker_id')->unsigned()->index('favorite_workers_worker_id_foreign');
			$table->bigInteger('home_id')->unsigned()->index('favorite_workers_home_id_foreign');
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
        Schema::dropIfExists('favorite_workers');
    }
}
