<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_category_id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('min_land_req')->nullable();
            $table->string('no_of_floor')->nullable();
            $table->string('total_space_per_floor')->nullable();
            $table->string('structure')->nullable();
            $table->string('beds')->nullable();
            $table->string('bathrooms')->nullable();
            $table->string('balcony')->nullable();
            $table->string('garden')->nullable();
            $table->string('pool')->nullable();
            $table->string('garage_capacity')->nullable();
            $table->string('steel')->nullable();
            $table->string('fitting')->nullable();
            $table->boolean('status')->default(true);
            $table->text('images')->nullable();
            $table->text('feature_image')->nullable();
            $table->timestamps();
            //$table->foreign('building_category_id')->references('id')->on('building_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
