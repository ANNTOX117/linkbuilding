<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->nullable()->index('index_subtotal')->after('price');
            $table->json('discounts')->nullable()->after('subtotal');
            $table->decimal('partial', 10, 2)->nullable()->index('index_partial')->after('discounts');
            $table->decimal('tax', 10, 2)->nullable()->index('index_tax')->after('partial');
            $table->decimal('total', 10, 2)->nullable()->index('index_total')->after('tax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('subtotal', 'discounts', 'partial', 'tax', 'total');
        });
    }
}
