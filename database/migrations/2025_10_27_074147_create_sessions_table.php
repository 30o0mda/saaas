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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId(column: 'parent_id')->nullable()->constrained('sessions')->onDelete('cascade');
            $table->foreignId(column: 'organization_id')->nullable()->constrained('organizations')->onDelete('cascade');
            $table->string('file')->nullable();
            $table->string('link')->nullable();
            $table->integer('order');
            $table->boolean(column: 'is_active')->default(1)->comment('1 = active, 0 = inactive')->nullable();
            $table->unsignedTinyInteger(column: 'type')->nullable()->default(1)->comment('1 = video, 2 = audio , 3 = pdf');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
