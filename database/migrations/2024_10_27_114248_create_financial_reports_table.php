<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinancialReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->string('month'); // Stores the month of the report, e.g., "January"
            $table->string('zone'); // Stores the zone information, e.g., "Zone 1"
            $table->decimal('total_a', 10, 2)->default(0); // Stores the total amount for Form A
            $table->decimal('total_b', 10, 2)->default(0); // Stores the total amount for Form B
            $table->decimal('grand_total', 10, 2)->default(0); // Stores the grand total
            $table->string('pic_name'); // Stores the name of the PIC
            $table->string('pic_signature')->nullable(); // Stores the signature of the PIC (can be a file path or encoded image)
            $table->timestamps(); // Adds created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_reports');
    }
}
