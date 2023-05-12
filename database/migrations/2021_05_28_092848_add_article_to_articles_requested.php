<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArticleToArticlesRequested extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles_requested', function (Blueprint $table) {
            $table->foreignId('article')->nullable()->index('index_article')->after('writer');
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
        Schema::table('articles_requested', function (Blueprint $table) {
            //
        });
    }
}
