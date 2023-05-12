<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order', 10)->index('index_order');
            $table->string('item', 50);
            $table->bigInteger('identifier')->index('index_identifier');
            $table->json('details')->nullable();
            $table->decimal('price', 10, 2)->index('index_price');
            $table->string('payment', 50)->index('index_payment');
            $table->enum('status', ['open', 'paid', 'failed', 'canceled', 'expired'])->default('open')->index('index_status');
            $table->foreignId('user')->index('index_user');
            $table->foreign('user')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
