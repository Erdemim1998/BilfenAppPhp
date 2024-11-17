<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('UserName')->unique();
            $table->string('Email')->unique();
            $table->string('Password')->unique();
            $table->string('PasswordHash')->unique();
            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');
            $table->foreignId('RoleId')->constrained("roles", "Id")->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
