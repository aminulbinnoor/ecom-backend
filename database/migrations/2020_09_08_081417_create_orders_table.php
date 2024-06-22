<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->bigInteger('user_id');
            $table->text('shipping_address')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('delivery_method')->nullable();
            $table->float('delivery_charge', 8, 2)->nullable();
            $table->float('discount', 8, 2)->nullable();
            $table->float('coupon_discount', 8, 2)->nullable();
            $table->bigInteger('coupon_id')->nullable();
            $table->float('sub_total', 8, 2)->nullable();
            $table->float('total', 8, 2)->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('status'); // multiple status [].
            $table->string('order_status');
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
