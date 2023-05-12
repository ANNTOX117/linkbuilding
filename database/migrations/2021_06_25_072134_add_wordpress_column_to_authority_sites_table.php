<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWordpressColumnToAuthoritySitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authority_sites', function (Blueprint $table) {
            $table->foreignId('wordpress')->nullable()->index('index_wordpress')->after('site');
            $table->foreign('wordpress')->references('id')->on('wordpress')->cascadeOnUpdate()->cascadeOnDelete();
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
            $table->dropColumn('wordpress');
        });
    }
}
