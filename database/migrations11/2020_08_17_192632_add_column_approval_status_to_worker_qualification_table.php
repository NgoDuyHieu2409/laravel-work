<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnApprovalStatusToWorkerQualificationTable extends Migration
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
	    $table->integer('approval_status')->nullable()->after('expiration_date')->comment('承認ステータス');
	    $table->dropColumn('approval_yn');
	    
	    
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
