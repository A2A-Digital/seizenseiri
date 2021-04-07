<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('member_number')->nullable()->uniqid();
            $table->integer('organization_id')->nullable();
            $table->string('name')->nullable();
            $table->string('member_kananame')->nullable();
            $table->string('email')->nullable();
            $table->string('email_two')->nullable();
            $table->string('postcode')->nullable();
            $table->string('fax_num')->nullable();
            $table->string('password')->nullable();
            $table->string('raw_password')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('phone')->uniqid();
            $table->string('telephone2')->nullable();
            $table->string('companyname')->nullable();
            $table->string('line_id');
            $table->string('facebook_id');
            $table->string('birth')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('users');
    }
}
