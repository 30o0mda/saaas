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
        Schema::create('organization_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('phone')->nullable();
            $table->string('password')->nullable();
            $table->text('image')->nullable();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('cascade');
            $table->unsignedTinyInteger(column: 'type')->nullable()->default(1)->comment('1 = Teacher, 2 = assistant');
            $table->boolean(column: 'is_master')->default(0)->comment('1 = active, 0 = inactive')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('organization_employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_employees');
    }
};
