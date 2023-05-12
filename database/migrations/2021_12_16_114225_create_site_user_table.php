<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site')->index('index_site');
            $table->foreign('site')->references('id')->on('sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user')->index('index_user');
            $table->foreign('user')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('site_user');
    }
}
