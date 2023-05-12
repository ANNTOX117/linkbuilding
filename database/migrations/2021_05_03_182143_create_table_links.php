<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('url')->index('index_url');
            $table->integer('anchor')->index('index_anchor');
            $table->boolean('follow')->nullable()->index('index_follow');
            $table->string('alt', 250)->nullable();
            $table->boolean('active')->nullable()->index('index_active');
            $table->foreignId('authority_site')->index('index_authority_site');
            $table->dateTime('ends_at')->nullable()->index('index_ends_at');
            $table->dateTime('visible_at')->nullable()->index('index_visible_at');
            $table->foreignId('client')->index('index_client');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->datetime('deleted_at')->nullable();
            $table->foreign('authority_site')->references('id')->on('authority_sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('client')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
    }
}
