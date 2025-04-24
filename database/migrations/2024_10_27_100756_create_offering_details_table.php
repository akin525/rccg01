<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('offering_details');
        Schema::create('offering_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offering_id')->constrained('offerings')->onDelete('cascade');
            $table->integer('denomination');
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
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
        Schema::dropIfExists('offering_details');
    }
}
