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
            $table->time('late_time')->default('00:15:00')->nullable();
            $table->time('early_time')->default('00:15:00')->nullable();
            $table->timestamps();
        });

        // Insert default shift using Eloquent
        Shift::create([
            'name' => 'Main',
            'start_time' => '10:00:00',
            'end_time' => '20:00:00',
            'saturday' => true,
            'sunday' => true,
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => false, // Friday off
            'late_time' => '00:00:00',
            'early_time' => '00:00:00',
        ]);

        Shift::create([
            'name' => 'Special',
            'start_time' => '10:00:00',
            'end_time' => '18:00:00',
            'saturday' => false, // Saturday off
            'sunday' => true,
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => false, // Friday off
            'late_time' => '00:15:00',
            'early_time' => '00:15:00',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
