<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnWorktimeToWorkRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_records', function (Blueprint $table) {
            //
            $table->float('nighttime_worktime', 3, 1)->nullable()->after('resttime_minutes')->comment('通常勤務時間');
            $table->float('overtime_worktime', 3, 1)->nullable()->after('resttime_minutes')->comment('通常勤務時間');
            $table->float('base_worktime', 3, 1)->nullable()->after('resttime_minutes')->comment('通常勤務時間');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_records', function (Blueprint $table) {
            //
        });
    }
}
