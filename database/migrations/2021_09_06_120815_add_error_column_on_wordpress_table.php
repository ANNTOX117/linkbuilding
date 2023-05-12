<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddErrorColumnOnWordpressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wordpress', function (Blueprint $table) {
            $table->boolean('error')->nullable()->after('password');
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
            $table->dropColumn('error');
        });
    }
}
