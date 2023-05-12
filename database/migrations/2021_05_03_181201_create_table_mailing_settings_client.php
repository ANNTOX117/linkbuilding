<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMailingSettingsClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailing_settings_client', function (Blueprint $table) {
            $table->id();
            $table->string('notification_link_expire', 250)->index('index_notification_link_expire');
            $table->foreignId('client')->index('index_client');
            $table->string('newsletter', 160)->index('index_newsletter');
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
        Schema::dropIfExists('mailing_settings_client');
    }
}
