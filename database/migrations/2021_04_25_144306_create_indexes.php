<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('id');
        });

        Schema::table('currencies', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index('user_id');
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
            $table->dropIndex('id');
        });

        Schema::table('currencies', function (Blueprint $table) {
            $table->dropIndex('created_at');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('user_id');
        });
    }
}
