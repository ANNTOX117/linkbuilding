<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableArticleRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('max_words')->nullable()->index('max_words');
            $table->integer('max_links')->nullable()->index('max_links');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_rules');
    }
}
