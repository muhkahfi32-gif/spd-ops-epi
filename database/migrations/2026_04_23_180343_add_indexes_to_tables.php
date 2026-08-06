<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travels', function (Blueprint $table) {
            $table->index('start_date');
            $table->index('end_date');
            $table->index('status');
            $table->index('destination');
        });
        
        Schema::table('employees', function (Blueprint $table) {
            $table->index('name');
            $table->index('email');
            $table->index('is_active');
        });
        
        Schema::table('employee_travel', function (Blueprint $table) {
            $table->index(['employee_id', 'travel_id']);
        });
    }

    public function down(): void
    {
        Schema::table('travels', function (Blueprint $table) {
            $table->dropIndex(['start_date']);
            $table->dropIndex(['end_date']);
            $table->dropIndex(['status']);
            $table->dropIndex(['destination']);
        });
        
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['email']);
            $table->dropIndex(['is_active']);
        });
        
        Schema::table('employee_travel', function (Blueprint $table) {
            $table->dropIndex(['employee_id', 'travel_id']);
        });
    }
};