<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDiscountsDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts_default', function (Blueprint $table) {
            $table->id();
            $table->decimal('price', 10, 2)->nullable()->index('index_price');
            $table->integer('years')->nullable()->index('index_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts_default');
    }
}
