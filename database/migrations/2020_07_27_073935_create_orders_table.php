<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('service_id')->unsigned();
            $table->text('address')->nullable();
            $table->string('unit')->nullable();
            $table->text('desc')->nullable();
            $table->string('hours')->nullable();
            $table->dateTime('schedule')->nullable();
            $table->string('trust_fees')->nullable();
            $table->string('tax')->nullable();
            $table->string('min_price')->nullable();
            $table->tinyInteger('quick_service')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->string('promo');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}
