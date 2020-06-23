<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->string('slug')->after('name')->nullable();;
            $table->string('display_name')->after('name');
            $table->string('group_slug')->after('guard_name')->nullable();
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->string('slug')->after('name')->nullable();;
            $table->string('display_name')->after('name');
            $table->string('group_slug')->after('guard_name')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('display_name');
            $table->dropColumn('group_slug');
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('display_name');
            $table->dropColumn('group_slug');
        });
    }
}
