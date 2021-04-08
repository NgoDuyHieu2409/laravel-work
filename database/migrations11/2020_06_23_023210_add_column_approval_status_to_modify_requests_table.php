<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnApprovalStatusToModifyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modify_requests', function (Blueprint $table) {
	    $table->integer('approval_status')->after('transportation_fee')->comment('承認ステータス');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modify_requests', function (Blueprint $table) {
            //
        });
    }
}
