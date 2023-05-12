
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFootersToSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->text('footer2')->nullable()->after('footer');
            $table->text('footer3')->nullable()->after('footer2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            //
        });
    }
}
