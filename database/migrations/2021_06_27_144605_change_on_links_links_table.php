<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeOnLinksLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {

            $table->foreignId('authority_site')->nullable()->change();
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
        Schema::table('links', function (Blueprint $table) {
            //
        });
    }
}
