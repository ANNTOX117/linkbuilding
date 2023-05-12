<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePackagesSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages_sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package')->index('index_package');
            $table->foreignId('authority_site')->index('index_authority_site');
            $table->foreignId('category')->index('index_category');
            $table->foreign('package')->references('id')->on('packages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('authority_site')->references('id')->on('authority_sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('category')->references('id')->on('categories')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages_sites');
    }
}
