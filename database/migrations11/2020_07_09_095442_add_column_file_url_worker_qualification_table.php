<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFileUrlWorkerQualificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('worker_qualification', function (Blueprint $table) {
            //
	    $table->text('file_url')->nullable()->after('worker_id')->comment('資格証ファイルURL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('worker_qualification', function (Blueprint $table) {
            //
        });
    }
}
