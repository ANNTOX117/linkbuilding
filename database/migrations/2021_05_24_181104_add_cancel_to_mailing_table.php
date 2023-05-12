<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelToMailingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailing', function (Blueprint $table) {
            $table->boolean('active')->nullable()->default('1')->index('index_active')->after('interval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mailing', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
