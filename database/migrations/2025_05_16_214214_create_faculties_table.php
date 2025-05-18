<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();

            $table->string('university'); 

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('application_link')->nullable();
            $table->string('admission_notes')->nullable();

            $table->string('open_day_dates')->nullable();
            $table->string('open_day_url')->nullable();

            $table->string('exam_dates')->nullable();

            $table->string('application_fee')->nullable();

            $table->string('application_deadlines')->nullable();

            $table->text ('bc_programs')->nullable();
            $table->text ('mgr_programs')->nullable();
            $table->text ('dr_programs')->nullable();

            $table->string('logo_url')->nullable();
            $table->string('banner_url')->nullable();

            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();

            $table->string('fields_of_study')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};