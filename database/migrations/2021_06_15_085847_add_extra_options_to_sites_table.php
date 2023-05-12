<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraOptionsToSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->string('header', 160)->nullable()->index('index_header')->after('logo');
            $table->char('menu', 7)->nullable()->index('index_menu')->after('header');
            $table->char('links', 7)->nullable()->index('index_links')->after('menu');
            $table->char('box', 7)->nullable()->index('index_box')->after('links');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('header', 'menu', 'links', 'box');
        });
    }
}
