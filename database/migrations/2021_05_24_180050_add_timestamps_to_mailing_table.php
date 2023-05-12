<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToMailingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailing', function (Blueprint $table) {
            $table->timestamp('created_at')->useCurrent()->after('interval');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate()->after('created_at');
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
            $table->dropColumn('created_at', 'updated_at');
        });
    }
}
