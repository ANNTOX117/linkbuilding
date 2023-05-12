<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableArticlesRequested extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles_requested', function (Blueprint $table) {
            $table->foreignId('order')->nullable()->index('index_order')->after('status');
            $table->string('url', 255)->nullable()->index('index_url')->after('status');
            $table->string('title', 160)->nullable()->index('index_title')->after('url');
            $table->text('description')->nullable()->after('title');
            $table->string('meta_title', 60)->nullable()->index('index_meta_title')->after('description');
            $table->string('meta_description', 160)->nullable()->index('index_meta_description')->after('meta_title');
            $table->mediumText('keywords')->nullable()->after('meta_description');
            $table->string('image', 255)->nullable()->after('keywords');
            $table->date('visible_at')->nullable()->after('image');
            $table->date('expired_at')->nullable()->after('visible_at');
            $table->foreignId('authority_site')->nullable()->index('index_authority_site')->after('expired_at');
            $table->foreignId('language')->nullable()->index('index_language')->after('authority_site');
            $table->foreignId('category')->nullable()->index('index_category')->after('language');
            $table->foreign('order')->references('id')->on('orders')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('authority_site')->references('id')->on('authority_sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('language')->references('id')->on('languages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('category')->references('id')->on('packages_categories')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles_requested', function (Blueprint $table) {
            //
        });
    }
}
