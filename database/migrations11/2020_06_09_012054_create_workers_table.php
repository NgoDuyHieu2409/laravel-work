<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workers', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('uid')->nullable()->comment('UID');
			$table->string('token')->nullable()->comment('TOKEN');
			$table->string('first_name')->comment('姓');
			$table->string('last_name')->comment('名');
			$table->string('first_name_kana')->comment('セイ');
			$table->string('last_name_kana')->comment('メイ');
			$table->integer('sex')->nullable()->comment('性別');
			$table->date('birthday')->comment('誕生日');
			$table->text('photo', 65535)->nullable()->comment('写真');
			$table->integer('good_percentages')->nullable()->comment('グッド率');
			$table->integer('total_workcount')->nullable()->comment('総業務回数');
			$table->integer('total_worktime')->nullable()->comment('総業務時間');
			$table->integer('penalty_point')->nullable()->comment('ペナルティポイント');
			$table->integer('cancel_percentages')->nullable()->comment('キャンセル率');
			$table->integer('immediately_cancel_percentages')->nullable()->comment('直前キャンセル率');
			$table->integer('bank_id')->nullable()->comment('銀行ID');
			$table->integer('bank_kind')->nullable()->comment('銀行種別');
			$table->integer('bank_shop_id')->nullable()->comment('銀行支店ID');
			$table->integer('bank_account_no')->nullable()->comment('口座番号');
			$table->integer('bank_account_name')->nullable()->comment('口座名');
			$table->softDeletes();
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
		Schema::drop('workers');
	}

}
