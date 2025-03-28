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
        Schema::table('salary_card_components', function (Blueprint $table) {
            $table->enum('calculation_type', ['fixed', 'percentage'])->default('fixed')->after('amount');
            $table->double('original_value')->nullable()->after('calculation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_card_components', function (Blueprint $table) {
            $table->dropColumn(['calculation_type', 'original_value']);
        });
    }
};
