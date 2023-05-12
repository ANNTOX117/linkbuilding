<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWordpressToExternalLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_links', function (Blueprint $table) {

            $table->foreignId('wordpress')->nullable()->index('index_wordpress')->after('authority_site');
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
        Schema::table('external_links', function (Blueprint $table) {
            //
        });
    }
}
