<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupToDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->foreignId('group')->index('index_group')->after('id');
            $table->foreign('group')->references('id')->on('group_discounts')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            //
        });
    }
}
