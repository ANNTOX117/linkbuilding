<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBothOnSelectWordpressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wordpress', function (Blueprint $table) {
            DB::statement("ALTER TABLE `wordpress` CHANGE COLUMN `type` `type` ENUM('article','sidebar','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL  COMMENT '' AFTER `url`;");
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
            //
        });
    }
}
