<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ImagePath')->nullable();
            $table->string('TCKN');
            $table->string('MotherName');
            $table->string('FatherName');
            $table->string('BirthDate');
            $table->string('Gender');
            $table->string('CivilStatus');
            $table->string('EmploymentDate');
            $table->string('MilitaryStatus')->nullable();
            $table->string('PostponementDate')->nullable();
            $table->string('CountryId')->default("TR");
            $table->foreign('CountryId')->references('Id')->on('countries')->onUpdate('cascade')->onDelete('cascade');
            $table->string('CityId')->default("IST");
            $table->foreign('CityId')->references('Id')->on('cities')->onUpdate('cascade')->onDelete('cascade');
            $table->string('DistrictId')->default("BEY");
            $table->foreign('DistrictId')->references('Id')->on('districts')->onUpdate('cascade')->onDelete('cascade');
            $table->string('Address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
