<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthoritySitesColumnPreviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authority_sites', function (Blueprint $table) {
            $table->text('preview')->nullable()->after('type');
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
            $table->dropColumn('preview');
        });
    }
}
