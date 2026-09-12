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
    Schema::table('consultation_messages', function (Blueprint $table) {
        $table->json('quick_replies')->nullable()->after('message');
    });
}

public function down(): void
{
    Schema::table('consultation_messages', function (Blueprint $table) {
        $table->dropColumn('quick_replies');
    });
}
};
