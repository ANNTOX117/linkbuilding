<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('site')->index('index_site')->after('ip');
            $table->string('kvk_number', 25)->index('index_kvk_number')->after('remember_token');
            $table->string('company', 50)->index('index_company')->after('lastname');
            $table->dateTime('last_login')->nullable()->index('index_last_login')->after('password');
            $table->decimal('tax', 3, 2)->index('index_tax')->after('kvk_number');
            $table->string('city', 50)->index('index_city')->after('remember_token');
            $table->string('country', 50)->index('index_country')->after('city');
            $table->string('credit', 50)->index('index_credit')->after('tax');
            $table->boolean('blocked')->nullable()->index('index_blocked')->after('role');
            $table->string('postal_code', 10)->nullable()->index('index_postal_code')->after('company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
