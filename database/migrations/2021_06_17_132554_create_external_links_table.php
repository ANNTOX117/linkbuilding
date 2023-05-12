<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('external_links', function (Blueprint $table) {  
            $table->id();
            $table->boolean('type')->nullable()->index('index_type');
            $table->boolean('active')->nullable()->index('index_active');
            
            $table->foreignId('article')->nullable()->index('index_article');
            $table->foreign('article')->references('id')->on('articles')->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->foreignId('authority_site')->nullable()->index('index_authority_site');
            $table->foreign('authority_site')->references('id')->on('authority_sites')->cascadeOnUpdate()->cascadeOnDelete();
            
            $table->date('visible_at')->nullable()->index('index_visible_at');
            $table->date('ends_at')->nullable()->index('index_ends_at');
            
            $table->dateTime('approved_at')->nullable()->index('index_approved_at');
            $table->dateTime('published_at')->nullable()->index('index_published_at');
            $table->text('url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('external_links');
    }
}
