<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer')->index('index_customer');
            $table->string('transaction', 255)->index('index_transaction');
            $table->string('key', 255)->nullable()->index('index_key');
            $table->decimal('amount', 10, 2)->nullable()->index('index_amount');
            $table->integer('bank')->index('index_bank');
            $table->foreignId('package')->index('index_package');
            $table->datetime('limit')->nullable()->index('index_limit');
            $table->enum('status', array('pending', 'success', 'error', 'cancelled'))->default('pending')->index('index_status');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->datetime('deleted_at')->nullable();
            $table->foreign('customer')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('package')->references('id')->on('packages')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
