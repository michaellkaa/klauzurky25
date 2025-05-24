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
       Schema::create('events', function (Blueprint $table) {
    $table->id();
    $table->string('university'); // název univerzity jako ve scraperu
    $table->string('faculty');    // název fakulty
    $table->date('date');         // datum události
    $table->string('type')->nullable(); // typ akce: DOD, deadline apod.
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
