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
    Schema::create('medicine_scans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('medicine_name')->nullable();
        $table->string('category')->nullable();
        $table->string('dosage_form')->nullable();
        $table->string('packaging')->nullable();
        $table->text('composition')->nullable();
        $table->text('indication')->nullable();
        $table->text('side_effects')->nullable();
        $table->text('contraindication')->nullable();
        $table->string('image_path')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_scans');
    }
};
