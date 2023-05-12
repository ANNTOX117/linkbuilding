<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthorityUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authority_user', function (Blueprint $table) {

            $table->id();
            $table->foreignId('authority')->index('index_authority');
            $table->foreign('authority')->references('id')->on('authority_sites')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('user')->index('index_user');
            $table->foreign('user')->references('id')->on('users')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authority_user');
    }
}
