<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackagesToRulesDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rules_discounts', function (Blueprint $table) {
            DB::statement("ALTER TABLE `rules_discounts` CHANGE COLUMN `product` `product` ENUM('startingpage','homepagelink','childstartingpage','intext','blogs','packages') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL  COMMENT '' AFTER `group`;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rules_discounts', function (Blueprint $table) {
            //
        });
    }
}
