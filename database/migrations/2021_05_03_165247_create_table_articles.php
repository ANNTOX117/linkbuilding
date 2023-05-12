<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title', 160)->index('index_title');
            $table->string('description', 250)->index('index_description');
            $table->string('meta_title', 60)->nullable()->index('index_meta_title');
            $table->string('meta_description', 160)->nullable()->index('index_meta_description');
            $table->mediumText('keywords')->nullable();
            $table->boolean('active')->nullable()->index('index_active');
            $table->boolean('draft')->nullable()->index('index_draft');
            $table->foreignId('authority_site')->index('index_authority_site');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->datetime('deleted_at')->nullable();
            $table->foreign('authority_site')->references('id')->on('authority_sites')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
