<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Shift;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time')->default('10:00:00');
            $table->time('end_time')->default('20:00:00');
            $table->boolean('saturday')->default('1');
            $table->boolean('sunday')->default('1');
            $table->boolean('monday')->default('1');
            $table->boolean('tuesday')->default('1');
            $table->boolean('wednesday')->default('1');
            $table->boolean('thursday')->default('1');
            $table->boolean('friday')->default('0');
            $table->time('late_time')->default('10:15:00')->nullable();
            $table->time('early_time')->default('19:45:00')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
