<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFkOnArticlesRequestedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles_requested', function (Blueprint $table) {
            $table->dropForeign('articles_requested_category_foreign');
            $table->foreignId('category')->nullable()->change();
            $table->foreign('category')->references('id')->on('categories')->cascadeOnUpdate()->nullOnDelete()->change();
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
