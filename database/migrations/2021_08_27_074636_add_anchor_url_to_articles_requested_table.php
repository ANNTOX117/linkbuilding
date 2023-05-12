<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnchorUrlToArticlesRequestedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles_requested', function (Blueprint $table) {
            $table->string('suggested_url', 255)->nullable()->after('category');
            $table->string('suggested_anchor', 250)->nullable()->after('suggested_url');
            $table->string('suggested_url2', 255)->nullable()->after('suggested_anchor');
            $table->string('suggested_anchor2', 250)->nullable()->after('suggested_url2');
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
            $table->dropColumn('suggested_url', 'suggested_anchor', 'suggested_url2', 'suggested_anchor2');
        });
    }
}
