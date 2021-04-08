<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkPrefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_prefs', function (Blueprint $table) {
	    $table->integer('id');
	    $table->string('name', 16)->comment('都道府県名');
	    $table->integer('sortno')->comment('ソート順');
	    $table->integer('work_count')->comment('仕事数');
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
        Schema::dropIfExists('work_prefs');
    }
}
