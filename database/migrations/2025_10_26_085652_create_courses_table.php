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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->foreignId('stage_and_subject_id')->nullable()->constrained('stage_and_subjects')->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('cascade');
            $table->integer('price');
            $table->integer('order');
            $table->boolean(column: 'is_active')->default(1)->comment('1 = active, 0 = inactive')->nullable();
            $table->boolean(column: 'is_free')->default(0)->comment('1 = free, 0 = not free')->nullable();
            $table->foreignId('organization_employees_id')->nullable()->constrained('organization_employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
