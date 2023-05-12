<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeArticleOnPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('package')->nullable()->change();
            $table->enum('type', array('Package', 'Requested article'))->nullable()->index('index_type')->after('bank');
            $table->foreignId('article')->nullable()->index('index_article')->after('package');
            $table->foreign('article')->references('id')->on('articles_requested')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
}
