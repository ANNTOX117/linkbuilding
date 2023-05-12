<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSettingsUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_user', function (Blueprint $table) {
            $table->id();
            $table->boolean('value')->index('index_value');
            $table->foreignId('option')->index('index_option');
            $table->foreign('option')->references('id')->on('settings')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('settings_user');
    }
}
