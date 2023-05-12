<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreteTableRulesDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount')->index('index_discount');
            $table->foreignId('user')->nullable()->index('index_user');
            $table->foreignId('group')->nullable()->index('index_group');
            $table->enum('product', ['startingpage', 'homepagelink', 'childstartingpage', 'intext', 'blogs'])->nullable()->index('index_product');
            $table->decimal('price', 10, 2)->nullable()->index('index_price');
            $table->boolean('active')->nullable()->index('index_active');
            $table->foreign('discount')->references('id')->on('group_discounts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('user')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('group')->references('id')->on('groups')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rules_discounts');
    }
}
