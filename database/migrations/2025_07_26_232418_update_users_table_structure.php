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
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing full_name column
            $table->dropColumn('full_name');

            // Add the new name fields
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('middle_name')->nullable()->after('last_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the new name fields
            $table->dropColumn(['first_name', 'last_name', 'middle_name']);

            // Add back the full_name column
            $table->string('full_name')->after('id');
        });
    }
};
