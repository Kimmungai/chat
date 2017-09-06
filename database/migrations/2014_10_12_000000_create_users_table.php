<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name');
            $table->string('company_type')->nullable();
            $table->string('company_name_furigana');
            $table->string('first_name');
            $table->string('first_name_furigana');
            $table->string('last_name');
            $table->string('last_name_furigana');
            $table->string('address');
            $table->string('tel');
            $table->string('email')->unique();
            $table->string('password')->default(bcrypt(''));
            $table->tinyInteger('verified')->default(0);
            $table->tinyInteger('user_category');
            $table->string('email_token')->index()->nullable();
            $table->boolean('is_admin')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
