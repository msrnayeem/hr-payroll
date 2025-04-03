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
        Schema::create('salary_card_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_card_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->nullable()->constrained('users')->onDelete('set null'); // User at the time of action
            $table->string('action')->index(); // 'create' or 'update'
            $table->json('old_values')->nullable(); // Values before the action (null for create)
            $table->json('new_values'); // Values after the action
            $table->timestamp('changed_at'); // When the change occurred
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null'); // Who made the change (optional, if authenticated)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_card_histories');
    }
};
