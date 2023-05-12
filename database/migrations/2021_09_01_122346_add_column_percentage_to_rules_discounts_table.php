<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPercentageToRulesDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rules_discounts', function (Blueprint $table) {
            $table->decimal('percentage', 10, 2)->nullable()->index('index_percentage')->after('price');
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
            $table->dropColumn('percentage');
        });
    }
}
