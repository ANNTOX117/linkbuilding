<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnToWordpressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wordpress', function (Blueprint $table) {
            $table->enum('type', array('article', 'sidebar'))->nullable()->index('index_type')->after('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wordpress', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
