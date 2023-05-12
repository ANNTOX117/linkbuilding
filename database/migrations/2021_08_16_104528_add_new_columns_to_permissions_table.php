<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->char('dashboard', 4)->default('1111')->index('index_dashboard');
            $table->char('languages', 4)->default('1111')->index('index_languages');
            $table->char('categories', 4)->default('1111')->index('index_categories');
            $table->char('sites', 4)->default('1111')->index('index_sites');
            $table->char('wordpress', 4)->default('1111')->index('index_wordpress');
            $table->char('authorities', 4)->default('1111')->index('index_authorities');
            $table->char('articles', 4)->default('1111')->index('index_articles');
            $table->char('packages', 4)->default('1111')->index('index_packages');
            $table->char('links', 4)->default('1111')->index('index_links');
            $table->char('approvals', 4)->default('1111')->index('index_approvals');
            $table->char('users', 4)->default('1111')->index('index_users');
            $table->char('mailing', 4)->default('1111')->index('index_mailing');
            $table->char('taxes', 4)->default('1111')->index('index_taxes');
            $table->char('texts', 4)->default('1111')->index('index_texts');
            $table->char('payments', 4)->default('1111')->index('index_payments');
            $table->char('discounts', 4)->default('1111')->index('index_discounts');
            $table->char('general', 4)->default('1111')->index('index_general');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('dashboard', 'languages', 'categories', 'sites', 'wordpress', 'authorities', 'articles', 'packages', 'links', 'approvals', 'users', 'mailing', 'taxes', 'texts', 'payments', 'discounts', 'general');
        });
    }
}
