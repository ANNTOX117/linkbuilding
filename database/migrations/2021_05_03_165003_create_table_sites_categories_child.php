<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSitesCategoriesChild extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites_categories_child', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category')->index('index_category');
            $table->foreignId('site')->index('index_site');
            $table->foreign('category')->references('id')->on('categories')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('site')->references('id')->on('sites')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites_categories_child');
    }
}
