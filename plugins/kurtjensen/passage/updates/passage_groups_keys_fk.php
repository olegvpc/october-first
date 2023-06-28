<?php namespace KurtJensen\Passage\Updates;

use October\Rain\Database\Schema\Blueprint;
use Schema;
use October\Rain\Database\Updates\Migration;

class PassageGroupsKeysFk extends Migration
{
    public function up()
    {
         Schema::table('kurtjensen_passage_groups_keys', function(Blueprint $table) {
            $table->foreign('user_group_id')->references('id')->on('user_groups')->onDelete('cascade');
         });

         Schema::table('kurtjensen_passage_groups_keys', function(Blueprint $table) {
            $table->foreign('key_id')->references('id')->on('kurtjensen_passage_keys')->onDelete('cascade');
         });

         Schema::table('kurtjensen_passage_variances', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
         });

         Schema::table('kurtjensen_passage_variances', function(Blueprint $table) {
            $table->foreign('key_id')->references('id')->on('kurtjensen_passage_keys')->onDelete('cascade');
         });


    }

    public function down()
    {
        Schema::table('kurtjensen_passage_groups_keys', function(Blueprint $table) {
            $table->dropForeign('kurtjensen_passage_groups_keys_user_group_id_foreign');
        });

        Schema::table('kurtjensen_passage_groups_keys', function(Blueprint $table) {
            $table->dropForeign('kurtjensen_passage_groups_keys_kurtjensen_passage_keys_id_foreign');
        });

        Schema::table('kurtjensen_passage_variances', function(Blueprint $table) {
            $table->dropForeign('kurtjensen_passage_variances_user_id_foreign');
        });

        Schema::table('kurtjensen_passage_variances', function(Blueprint $table) {
            $table->dropForeign('kurtjensen_passage_variances_key_id_foreign');
        });

    }
}
