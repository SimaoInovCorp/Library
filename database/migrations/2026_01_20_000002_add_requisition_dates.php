<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dateTime('requested_at')->nullable()->after('status');
            $table->dateTime('expected_end_at')->nullable()->after('requested_at');
        });
    }

    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn(['requested_at', 'expected_end_at']);
        });
    }
};
