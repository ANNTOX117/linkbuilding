<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSiteToArticlesRequestedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles_requested', function (Blueprint $table) {
            $table->foreignId('site')->nullable()->index('index_site')->after('article');
            $table->foreign('site')->references('id')->on('authority_sites')->cascadeOnUpdate()->cascadeOnDelete();
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
