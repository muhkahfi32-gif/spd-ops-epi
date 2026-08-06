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
        // 1. Create pivot table for multi-employee surat dinas
        Schema::create('employee_surat_dinas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_dinas_id')->constrained('surat_dinas')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['surat_dinas_id', 'employee_id']);
        });

        // 2. Make employee_id nullable in surat_dinas table for backwards compatibility
        Schema::table('surat_dinas', function (Blueprint $table) {
            $table->foreignId('employee_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_surat_dinas');
    }
};
