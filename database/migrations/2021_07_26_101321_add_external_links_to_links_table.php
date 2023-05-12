<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExternalLinksToLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->timestamp('approved_at')->nullable()->index('index_approved_at')->after('visible_at');
            $table->timestamp('published_at')->nullable()->index('index_published_at')->after('approved_at');
            $table->text('external_url')->nullable()->after('published_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn('approved_at', 'published_at', 'external_url');
        });
    }
}
