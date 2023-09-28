<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->string('slug')->nullable();
            $table->string('type')->nullble();
            $table->string('regular_hours')->nullable();
            $table->string('after_hours')->nullable();
            $table->string('extra_hours_cost')->nullable();
            $table->string('item')->nullable();
            $table->string('comment')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('signature')->nullable();
            $table->string('payment_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
        Schema::dropIfExists('invoices');
    }
}
