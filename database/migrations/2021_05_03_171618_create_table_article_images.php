<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableArticleImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article')->index('index_article');
            $table->string('image', 250)->nullable()->index('index_image');
            $table->foreign('article')->references('id')->on('articles')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_images');
    }
}
