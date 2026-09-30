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
    Schema::table('medicine_reminders', function (Blueprint $table) {
        $table->boolean('notify_enabled')->default(true);
    });
}

public function down(): void
{
    Schema::table('medicine_reminders', function (Blueprint $table) {
        $table->dropColumn('notify_enabled');
    });
}
};
