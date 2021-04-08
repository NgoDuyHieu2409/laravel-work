<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('companies', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('email')->comment('メールアドレス');
			$table->string('password')->comment('パスワード');
			$table->string('remember_token')->nullable()->comment('TOKEN');
			$table->string('name')->comment('会社名');
			$table->string('name_kana')->comment('会社名カナ');
			$table->string('contact_name')->comment('担当者氏名');
			$table->string('contact_name_kana')->comment('担当者氏名カナ');
			$table->string('tel')->comment('電話番号');
			$table->string('zipcode')->comment('郵便番号');
			$table->integer('pref')->comment('都道府県');
			$table->string('city')->comment('市町村');
			$table->string('address1')->comment('住所1');
			$table->string('address2')->comment('住所2');
			$table->integer('mf_approve_status')->nullable()->comment('MF決済顧客審査ステータス');
			$table->text('website_url', 65535)->nullable()->comment('ウェブサイトURL');
			$table->string('representative_name')->comment('代表者名');
			$table->string('corporate_number')->nullable()->comment('法人番号');
			$table->text('business_descriptiona', 65535)->nullable()->comment('事業内容');
			$table->dateTime('admin_approval_at')->nullable()->comment('管理者承認日時');
			$table->integer('credit_limit')->nullable()->comment('与信限度額');
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
		Schema::drop('companies');
	}

}
