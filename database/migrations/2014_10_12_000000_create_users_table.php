<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('phone_verified')->nullable();
            $table->string('password');
            $table->text('profile_image')->nullable();
            $table->string('address',150)->nullable();
            $table->integer('otp')->nullable();
            $table->boolean('otp_used')->nullable();
            $table->timestamp('otp_expired')->nullable();
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
