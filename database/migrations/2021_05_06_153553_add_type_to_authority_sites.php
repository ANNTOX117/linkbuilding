<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToAuthoritySites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authority_sites', function (Blueprint $table) {
            $table->enum('type', array('wordpress', 'startingpage', 'childstartingpage'))->nullable()->index('index_type')->after('refering_domains');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authority_sites', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
