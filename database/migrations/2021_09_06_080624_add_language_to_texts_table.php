<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageToTextsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('texts', function (Blueprint $table) {
            $table->foreignId('language')->nullable()->index('index_language')->after('description');
            $table->foreign('language')->references('id')->on('languages')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('texts', function (Blueprint $table) {
            //
        });
    }
}
