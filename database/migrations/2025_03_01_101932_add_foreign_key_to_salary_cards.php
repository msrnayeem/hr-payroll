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
        if (Schema::hasTable('salary_cards')) {
            Schema::table('salary_cards', function (Blueprint $table) {
                if (Schema::hasColumn('salary_cards', 'employee_id')) {
                    $table->dropForeign(['employee_id']);
                    $table->dropColumn('employee_id');
                }

                if (Schema::hasColumn('salary_cards', 'created_by')) {
                    $table->dropForeign(['created_by']);
                    $table->dropColumn('created_by');
                }

                if (Schema::hasColumn('salary_cards', 'updated_by')) {
                    $table->dropForeign(['updated_by']);
                    $table->dropColumn('updated_by');
                }
            });
        }
    }
};
