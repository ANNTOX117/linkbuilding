<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAuthoritySites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authority_sites', function (Blueprint $table) {
            $table->id();
            $table->string('url', 75)->index('index_url');
            $table->ipAddress('subnet')->nullable();
            $table->integer('pa')->nullable()->index('index_pa');
            $table->integer('da')->nullable()->index('index_da');
            $table->integer('tf')->nullable()->index('index_tf');
            $table->integer('cf')->nullable()->index('index_cf');
            $table->integer('dre')->nullable()->index('index_dre');
            $table->integer('refering_domains')->nullable()->index('index_refering_domains');
            $table->decimal('price', 10, 2)->nullable()->index('index_price');
            $table->decimal('price_special', 10, 2)->nullable()->index('index_price_special');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->datetime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authority_sites');
    }
}
