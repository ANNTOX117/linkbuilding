<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColumnVisibilityOnSitesCategoriesMain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites_categories_main', function (Blueprint $table) {
            $table->boolean('visibility')->default(1)->nullable()->index('index_visibility');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites_categories_main', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }
}
