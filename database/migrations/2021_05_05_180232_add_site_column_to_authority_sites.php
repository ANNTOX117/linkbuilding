<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSiteColumnToAuthoritySites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authority_sites', function (Blueprint $table) {
            $table->foreignId('site')->index('index_site')->after('id');
            $table->foreign('site')->references('id')->on('sites')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authority_sites', function (Blueprint $table) {
            //
        });
    }
}
