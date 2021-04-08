<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_branches', function (Blueprint $table) {
            $table->bigIncrements('id');
	    $table->integer('hiragana_id')->comment('あいうえお番号');
	    $table->integer('gyou_id')->comment('行番号');
	    $table->string('bank_code', 4)->comment('銀行コード');
	    $table->string('branch_code', 3)->comment('支店コード');
	    $table->string('branch_name')->comment('支店名');
	    $table->string('branch_name_kana')->comment('支店名フリガナ');
	    $table->string('address')->comment('住所');
	    $table->string('tel')->comment('電話番号');
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
        Schema::dropIfExists('bank_branches');
    }
}
