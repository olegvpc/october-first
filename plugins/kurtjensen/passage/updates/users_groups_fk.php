<?php namespace KurtJensen\Passage\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class UsersGroupsFk extends Migration
{
    public function up()
    {
        Schema::table('users_groups', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
         });

        Schema::table('users_groups', function(Blueprint $table) {
            $table->foreign('user_group_id')->references('id')->on('user_groups')->onDelete('cascade');
         });

    }

    public function down()
    {
        Schema::table('users_groups', function(Blueprint $table) {
            $table->dropForeign('users_groups_user_id_foreign');
        });

        Schema::table('users_groups', function(Blueprint $table) {
            $table->dropForeign('users_groups_user_group_id_foreign');
        });

    }
}
