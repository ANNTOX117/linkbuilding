<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeaturedColumnToAuthoritySitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authority_sites', function (Blueprint $table) {
            $table->boolean('featured')->nullable()->index('index_featured')->after('price_special');
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
            $table->dropColumn('featured');
        });
    }
}
