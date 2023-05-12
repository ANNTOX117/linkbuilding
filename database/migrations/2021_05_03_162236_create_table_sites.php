<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name', 160)->index('index_name');
            $table->string('url', 75)->index('index_url');
            $table->string('logo', 250)->nullable()->index('index_logo');
            $table->enum('type', array('Link building system', 'Selling system'))->nullable()->index('index_type');
            $table->char('currency', 3)->nullable()->index('index_currency');
            $table->boolean('automatic')->nullable()->index('index_automatic');
            $table->ipAddress('ip')->nullable();
            $table->foreignId('language')->index('index_language');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->datetime('deleted_at')->nullable();
            $table->foreign('language')->references('id')->on('languages')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sites');
    }
}
