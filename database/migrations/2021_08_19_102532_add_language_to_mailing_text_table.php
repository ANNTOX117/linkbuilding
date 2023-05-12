<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageToMailingTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailing_text', function (Blueprint $table) {
            $table->foreignId('language')->nullable()->index('index_language');
            $table->foreign('language')->references('id')->on('languages')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mailing_text', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
}
