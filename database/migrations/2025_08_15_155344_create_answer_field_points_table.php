<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('answer_field_points', function (Blueprint $table) {
            $table->foreignId('answer_id')->constrained()->onDelete('cascade');
            $table->foreignId('field_id')->constrained()->onDelete('cascade');
            $table->integer('points');
            $table->primary(['answer_id', 'field_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answer_field_points');
    }
};
