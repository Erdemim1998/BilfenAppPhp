<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->string('Name');
            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
