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
    Schema::table('consultations', function (Blueprint $table) {
        $table->string('duration')->nullable()->after('main_complaint');
        $table->string('severity')->nullable()->after('duration');
        $table->string('additional_symptoms')->nullable()->after('severity');
        $table->string('has_taken_medicine')->nullable()->after('additional_symptoms');
    });
}

public function down(): void
{
    Schema::table('consultations', function (Blueprint $table) {
        $table->dropColumn(['duration', 'severity', 'additional_symptoms', 'has_taken_medicine']);
    });
}
};
