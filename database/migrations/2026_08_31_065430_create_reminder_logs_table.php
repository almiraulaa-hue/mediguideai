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
    Schema::create('reminder_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('medicine_reminder_id')->constrained()->onDelete('cascade');
        $table->date('scheduled_date');
        $table->enum('status', ['diminum', 'terlewat', 'belum'])->default('belum');
        $table->timestamp('taken_at')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_logs');
    }
};
