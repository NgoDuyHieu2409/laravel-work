<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnConfirmToWorkApplicationsTable extends Migration
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
	    $table->dateTime('confirmed_at')->nullable()->after('assigned_at')->comment('確認日時');
	    $table->string('confirm_yn', 1)->after('assigned_at')->comment('前日までの確認事項フラグ');
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
