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
    Schema::create('health_profiles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->date('birth_date')->nullable();
        $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
        $table->string('blood_type', 3)->nullable();
        $table->integer('height')->nullable();
        $table->json('allergies')->nullable();
        $table->json('chronic_diseases')->nullable();
        $table->json('routine_medicines')->nullable();
        $table->text('other_conditions')->nullable();
        $table->boolean('is_verified')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_profiles');
    }
};
