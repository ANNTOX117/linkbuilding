<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeJsonColumnsToArticlesRequested extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles_requested', function (Blueprint $table) {
            $table->json('suggested_url')->change();
            $table->json('suggested_anchor')->change();
            $table->dropColumn('suggested_url2', 'suggested_anchor2');
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
