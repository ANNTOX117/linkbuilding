<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMailing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email')->index('index_email');
            $table->string('subject', 50)->index('index_subject');
            $table->boolean('batch')->nullable()->index('index_batch');
            $table->integer('size')->nullable()->index('index_size');
            $table->integer('interval')->nullable()->index('index_interval');
            $table->foreign('email')->references('id')->on('mailing_text')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailing');
    }
}
