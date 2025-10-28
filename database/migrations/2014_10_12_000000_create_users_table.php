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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 20)->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('verify_code', 10)->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('image')->nullable();
            $table->unsignedTinyInteger(column: 'type')->nullable()->default(1)->comment('1 = student, 2 = parent ');
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
