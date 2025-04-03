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
        Schema::table('salary_cards', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_cards', function (Blueprint $table) {
            $table->dropForeign(['employee_id', 'created_by', 'updated_by']);
            $table->dropColumn('employee_id');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
};
