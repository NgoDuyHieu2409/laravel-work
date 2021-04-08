<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHomesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('homes', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('company_id')->unsigned()->index('homes_company_id_foreign')->comment('会社ID');
			$table->string('email')->comment('メールアドレス');
			$table->dateTime('email_verified_at')->nullable()->comment('メール確認日時');
			$table->string('password')->comment('パスワード');
			$table->string('remember_token')->nullable()->comment('TOKEN');
			$table->string('name')->comment('施設名');
			$table->string('name_kana')->comment('施設名カナ');
			$table->string('contact_name')->comment('担当者氏名');
			$table->string('contact_name_kana')->comment('担当者氏名カナ');
			$table->string('sub_contact_name')->nullable()->comment('サブ担当者氏名');
			$table->string('sub_contact_name_kana')->nullable()->comment('サブ担当者氏名カナ');
			$table->integer('type')->nullable()->comment('施設種別');
			$table->string('certification_no')->nullable()->comment('認証NO');
			$table->string('tel')->comment('電話番号');
			$table->string('zipcode')->nullable()->comment('郵便番号');
			$table->integer('pref')->nullable()->comment('都道府県');
			$table->string('city')->nullable()->comment('市町村');
			$table->string('address1')->nullable()->comment('住所1');
			$table->string('address2')->nullable()->comment('住所2');
			$table->float('lng')->nullable()->comment('経度');
			$table->float('lat')->nullable()->comment('緯度');
			$table->text('access', 65535)->nullable()->comment('アクセス');
			$table->text('website_url', 65535)->nullable()->comment('ウェブサイトURL');
			$table->text('photo_url', 65535)->nullable()->comment('施設画像URL');
			$table->text('description', 65535)->nullable()->comment('説明文');
			$table->text('qrcode_url', 65535)->nullable()->comment('QRコードURL');
			$table->dateTime('approval_at')->nullable()->comment('承認日時');
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
		Schema::drop('homes');
	}

}
