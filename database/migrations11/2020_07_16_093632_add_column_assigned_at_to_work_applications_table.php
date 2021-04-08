<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAssignedAtToWorkApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_applications', function (Blueprint $table) {
            //
	    $table->dateTime('assigned_at')->nullable()->after('status')->comment('確定日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_applications', function (Blueprint $table) {
            //
        });
    }
}
