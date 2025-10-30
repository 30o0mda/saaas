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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->longText('answer');
            $table->boolean(column: 'is_correct')->default(0)->comment('1 = correct, 0 = incorrect')->nullable();
            $table->string('media')->nullable();
            $table->unsignedTinyInteger(column: 'media_type')->nullable()->default(1)->comment('1 = picture,  2 = Audio , 3 = video');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
