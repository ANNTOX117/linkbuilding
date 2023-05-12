<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveColumnToSitesCategoriesChildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites_categories_child', function (Blueprint $table) {
            $table->boolean('active')->nullable()->default(1)->index('index_active')->after('site');
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
            $table->dropColumn('active');
        });
    }
}
