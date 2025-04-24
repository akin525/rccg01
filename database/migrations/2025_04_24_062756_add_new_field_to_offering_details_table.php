<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldToOfferingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offering_details', function (Blueprint $table) {
            $table->string('branch_id')->nullable(); // change the type if needed
            $table->string('offering_date')->nullable(); // change the type if needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offering_details', function (Blueprint $table) {
            $table->string('branch_id')->nullable(); // change the type if needed
            $table->string('offering_date')->nullable(); // change the type if needed
        });
    }
}
