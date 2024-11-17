<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('Id');
            $table->string('Name');
            $table->string('FilePath');
            $table->string('Status');
            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');
            $table->foreignId('UserId')->constrained("users", "Id")->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
