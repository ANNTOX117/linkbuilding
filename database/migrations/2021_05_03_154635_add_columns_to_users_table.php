<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastname', 50)->nullable()->index('index_lastname')->after('name');
            $table->foreignId('role')->nullable()->index('index_role')->after('password');
            $table->ipAddress('ip')->nullable()->after('remember_token');
            $table->softDeletes()->after('updated_at');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lastname', 'role', 'ip', 'delete_at']);
        });
    }
}
