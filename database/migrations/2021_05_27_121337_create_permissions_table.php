<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role')->index('index_role');
            $table->char('pages', 4)->default('1111')->index('index_pages');
            $table->char('packages', 4)->default('1111')->index('index_packages');
            $table->char('blogs', 4)->default('1111')->index('index_blogs');
            $table->foreign('role')->references('id')->on('roles')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
