<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePagesSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page')->nullable()->index('index_page');
            $table->foreignId('site')->nullable()->index('index_site');
            $table->foreign('page')->references('id')->on('pages')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('pages_sites');
    }
}
