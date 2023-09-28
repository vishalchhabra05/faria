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
            $table->string('slug');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            //$table->string('email')->unique()->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->unique()->nullable();
            $table->string('mobile_code')->nullable();
            $table->string('country')->nullable();
            $table->string('image')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('device_token')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('chat_count')->default(0);
            $table->integer('notification_count')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->Text('socialtoken')->nullable();
            $table->string('password')->nullable();
            $table->string('otp')->nullable();
            $table->integer('otp_verify')->default(0);
            $table->integer('notification_enable')->default(0);
            $table->longtext('descraption')->nullable();
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
