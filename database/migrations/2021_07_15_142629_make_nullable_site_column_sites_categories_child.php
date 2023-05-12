<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeNullableSiteColumnSitesCategoriesChild extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites_categories_child', function (Blueprint $table) {
            $table->foreignId('site')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites_categories_child', function (Blueprint $table) {
            //
        });
    }
}
