<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWorkerQualificationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('worker_qualification', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('worker_id')->unsigned()->index('worker_qualification_worker_id_foreign')->comment('ワーカーID');
			$table->bigInteger('qualification_id')->unsigned()->index('worker_qualification_qualification_id_foreign')->comment('資格ID');
			$table->string('identification_no')->nullable()->comment('照合番号');
			$table->dateTime('registration_date')->nullable()->comment('取得日');
			$table->dateTime('expiration_date')->nullable()->comment('有効期限');
			$table->string('approval_yn', 1)->nullable()->comment('承認フラグ');
			$table->integer('refusal_reason')->nullable()->comment('拒否理由');
			$table->text('memo', 65535)->nullable()->comment('メモ');
			$table->dateTime('approved_at')->nullable()->comment('承認日時');
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
		Schema::drop('worker_qualification');
	}

}
