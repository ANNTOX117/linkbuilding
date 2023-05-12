<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesRequestedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_requested', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer')->index('index_customer');
            $table->foreignId('writer')->nullable()->index('index_writer');
            $table->enum('status', array('pending', 'approved'))->default('pending')->index('index_status');
            $table->datetime('approved_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->datetime('deleted_at')->nullable();
            $table->foreign('customer')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('writer')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles_requested');
    }
}
