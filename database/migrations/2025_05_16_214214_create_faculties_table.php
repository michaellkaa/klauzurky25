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

            // Reference to the parent university
            $table->unsignedBigInteger('university_id');

            // Basic info
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // Admissions
            $table->string('application_link')->nullable();
            $table->text('admission_notes')->nullable();


            // Open Days
            $table->date('open_day_date')->nullable();
            $table->string('open_day_url')->nullable();

            $table->text('exam_dates')->nullable(); // lze rozparsovat na list v Pythonu
            $table->integer('application_fee_online')->nullable();
            $table->integer('application_fee_paper')->nullable();

            $table->date('application_deadline')->nullable();

            // Study programs by level
            $table->json('bc_programs')->nullable();
            $table->json('mgr_programs')->nullable();
            $table->json('dr_programs')->nullable();

            // Images
            $table->string('logo_url')->nullable();
            $table->string('banner_url')->nullable();

            $table->timestamps();

            $table->foreign('university_id')
                ->references('id')
                ->on('universities')
                ->onDelete('cascade');
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