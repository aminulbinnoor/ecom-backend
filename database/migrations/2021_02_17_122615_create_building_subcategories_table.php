<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingSubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('building_subcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_category_id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('image')->nullable();
            $table->text('short_description')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('building_subcategories');
    }
}
