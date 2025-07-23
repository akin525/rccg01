<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpowermentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empowerment', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('branch_id')->unsigned();
            $table->enum('title', ['Mr', 'Mrs', 'Miss','Dr (Mrs)', 'Dr', 'Prof', 'Chief', 'Chief (Mrs)', 'Engr', 'Surveyor', 'HRH','Elder','Oba','Olori']);
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email', 50)->unique();
            $table->string('phone')->nullable();
            $table->string('age')->nullable();
            $table->string('religion')->nullable();
            $table->string('course')->nullable();
            $table->string('info')->nullable();
            $table->enum('employment_status',['employed', 'unemployed', 'self-employed'])->nullable();
            $table->string('address')->nullable();
            $table->enum('sex', ['male', 'female']);
            $table->enum('marital_status', ['married', 'single'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empowerment');
    }
}
