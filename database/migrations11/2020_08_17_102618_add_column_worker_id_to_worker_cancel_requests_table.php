<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnWorkerIdToWorkerCancelRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('worker_cancel_requests', function (Blueprint $table) {
            //
	    $table->bigInteger('worker_id')->unsigned()->after('reason')->comment('ワーカーID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('worker_cancel_requests', function (Blueprint $table) {
            //
        });
    }
}
