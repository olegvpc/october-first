<?php

namespace Olegvpc\Contact\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateContactsTable extends Migration
{
    public function up()
    {
        Schema::create('olegvpc_contacts', function(Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 100);
            $table->string('content', 50);
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('olegvpc_contacts');
    }
}
