<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LinksOnExternalLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_links', function (Blueprint $table) {
            $table->foreignId('links')->nullable()->index('index_links')->after('article');
            $table->foreign('links')->references('id')->on('links')->cascadeOnUpdate()->cascadeOnDelete();
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
