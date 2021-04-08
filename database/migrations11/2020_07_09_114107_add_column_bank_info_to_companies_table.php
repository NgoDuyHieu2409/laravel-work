<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBankInfoToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
	    $table->integer('bank_account_name')->nullable()->after('credit_limit')->comment('口座名');
	    $table->integer('bank_account_no')->nullable()->after('credit_limit')->comment('口座番号');
	    $table->integer('bank_shop_id')->nullable()->after('credit_limit')->comment('銀行支店ID');
	    $table->integer('bank_kind')->nullable()->after('credit_limit')->comment('銀行種別');
	    $table->integer('bank_id')->nullable()->after('credit_limit')->comment('銀行ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
}
