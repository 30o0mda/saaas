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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->longText('question');
            $table->foreignId('question_bank_id')->constrained('question_banks')->onDelete('cascade');
            $table->string('media')->nullable();
            $table->integer('degree');
            $table->unsignedTinyInteger(column: 'media_type')->nullable()->default(1)->comment('1 = picture,  2 = Audio , 3 = video');
            $table->unsignedTinyInteger(column: 'question_type')->nullable()->default(1)->comment('1 = mcq, 2 =  true_false, 3 = article');
            $table->unsignedTinyInteger(column: 'difficulty')->nullable()->default(1)->comment('1 = easy, 2 =  medium, 3 = hard');
            $table->boolean(column: 'is_active')->default(1)->comment('1 = active, 0 = inactive')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
