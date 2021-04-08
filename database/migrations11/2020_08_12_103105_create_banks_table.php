<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->bigIncrements('id');
	    $table->integer('kind')->comment('業態');
	    $table->integer('hiragana_id')->comment('あいうえお番号');
	    $table->integer('gyou_id')->comment('行番号');
	    $table->string('bank_code', 4)->comment('銀行コード');
	    $table->string('bank_name')->comment('銀行名');
	    $table->string('bank_name_kana')->comment('銀行名カナ');
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
        Schema::dropIfExists('banks');
    }
}
