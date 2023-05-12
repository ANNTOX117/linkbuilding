<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidToArticlesRequestedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles_requested', function (Blueprint $table) {
            $table->boolean('paid')->nullable()->index('index_paid')->after('writer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles_requested', function (Blueprint $table) {
            $table->dropColumn('paid');
        });
    }
}
