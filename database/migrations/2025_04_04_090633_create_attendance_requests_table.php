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
        Schema::create('attendance_requests', function (Blueprint $table) {
            $table->id();

            // Reference to the users table for employee_id
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');

            // Attendance date and times
            $table->date('attendance_date');
            $table->time('entry_time');
            $table->time('exit_time');

            // Status of the request
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // Reason for the request (nullable)
            $table->text('reason')->nullable();

            // Reference to the users table for decided_by (HR/admin user)
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_requests');
    }
};
