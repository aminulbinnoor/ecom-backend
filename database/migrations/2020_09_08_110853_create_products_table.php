<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->nullable();
            $table->bigInteger('theme_id')->nullable();
            $table->bigInteger('composition_id')->nullable();
            $table->bigInteger('room_id')->nullable();
            $table->bigInteger('product_category_id');
            $table->bigInteger('product_sub_category_id');
            $table->string('product_category_name')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->float('price', 8, 2)->unsigned();
            $table->string('serial_no')->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount_price', 8, 2)->unsigned()->nullable();
            $table->float('discount_amount', 8, 2)->unsigned()->nullable();
            $table->text('short_description');
            $table->text('description')->nullable();
            $table->text('feature_image')->nullable();
            $table->text('images')->nullable();
            $table->string('instock');
            $table->integer('order_limit');
            $table->text('variations')->nullable();
            $table->text('specification_dimensions')->nullable();
            $table->text('specification_details')->nullable();
            $table->boolean('mobility')->default(0);
            $table->string('status'); // 1,2,3,4,5,6 example 6 for publish on market
            // $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            // $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade');
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
        Schema::dropIfExists('products');
    }
}
