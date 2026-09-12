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
    Schema::table('medicine_scans', function (Blueprint $table) {
        $table->string('manufacturer')->nullable()->after('packaging');
        $table->string('price_estimate')->nullable()->after('manufacturer');
    });
}

public function down(): void
{
    Schema::table('medicine_scans', function (Blueprint $table) {
        $table->dropColumn(['manufacturer', 'price_estimate']);
    });
}
};
