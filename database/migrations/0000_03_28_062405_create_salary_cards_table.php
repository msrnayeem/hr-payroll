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
        Schema::create('salary_cards', function (Blueprint $table) {
            $table->id();
            $table->double('basic_salary');
            $table->double('net_salary');
            $table->double('total_deduction');
            $table->double('total_earn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_cards');
    }
};
