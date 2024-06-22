<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compositions', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('category_id')->nullable();
          $table->unsignedBigInteger('theme_id')->nullable();
          $table->string('name')->nullable();
          $table->string('slug')->nullable();
          $table->text('short_description')->nullable();
          $table->text('description')->nullable();
          $table->text('images')->nullable();
          $table->double('costing_price', 15, 8)->nullable();
          $table->double('min_price', 15, 8)->nullable();
          $table->double('max_price', 15, 8)->nullable();
          $table->text('specification')->nullable();
          $table->string('composition_code')->nullable();
          $table->boolean('is_active')->default(true);
          $table->timestamps();
        //  $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compositions');
    }
}
