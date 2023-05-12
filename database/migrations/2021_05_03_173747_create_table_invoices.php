<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number', 25)->index('index_number');
            $table->foreignId('client')->index('index_client');
            $table->enum('status', array('open', 'reminder', 'concept', 'credit note', 'sent', '2nd reminder', '3rd reminder'))->nullable()->index('index_status');
            $table->foreignId('payment_method')->index('index_payment_method');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->datetime('deleted_at')->nullable();
            $table->foreign('client')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('payment_method')->references('id')->on('payment_methods')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
