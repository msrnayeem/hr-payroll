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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->date('attendance_date');

            // Foreign key: employee_id
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');

            $table->time('shift_start');
            $table->tinyInteger('late_entry_consider')->nullable();
            $table->time('entry_time');
            $table->boolean('is_late');

            $table->time('shift_end');
            $table->tinyInteger('early_out_consider')->nullable();
            $table->time('exit_time');
            $table->boolean('is_early');

            $table->boolean('is_manual')->nullable();

            // Foreign key: manual_by (nullable)
            $table->unsignedBigInteger('manual_by')->nullable();
            $table->foreign('manual_by')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
