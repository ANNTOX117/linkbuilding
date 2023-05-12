<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableBatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mailing')->index('index_mailing');
            $table->foreignId('customer')->index('index_customer');
            $table->date('send_at')->nullable()->index('index_send_at');
            $table->boolean('waiting')->nullable()->index('index_waiting');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->foreign('mailing')->references('id')->on('mailing')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('customer')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batches');
    }
}
