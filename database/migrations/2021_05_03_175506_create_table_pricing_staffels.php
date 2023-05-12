<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePricingStaffels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_staffels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role')->index('index_role');
            $table->integer('discount')->index('index_discount');
            $table->foreign('role')->references('id')->on('roles')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pricing_staffels');
    }
}
