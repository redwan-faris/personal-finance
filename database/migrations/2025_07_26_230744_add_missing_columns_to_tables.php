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
        // Add missing columns to wallet table
        Schema::table('wallets', function (Blueprint $table) {
            $table->uuid('user_id')->nullable()->after('name');
            $table->text('description')->nullable()->after('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Add missing columns to transaction table
        Schema::table('transactions', function (Blueprint $table) {
            $table->date('transaction_date')->nullable()->after('direction');
            $table->text('notes')->nullable()->after('transaction_date');
        });

        // Add missing columns to transaction_category table
        Schema::table('transaction_categories', function (Blueprint $table) {
            $table->enum('type', ['income', 'expense'])->default('expense')->after('description');
            $table->string('color', 7)->nullable()->after('type');
        });

        // Add missing columns to people table
        Schema::table('people', function (Blueprint $table) {
            $table->enum('type', ['customer', 'supplier', 'employee', 'other'])->nullable()->after('note');
            $table->text('address')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from wallet table
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'description']);
        });

        // Remove columns from transaction table
        Schema::table('transaction', function (Blueprint $table) {
            $table->dropColumn(['transaction_date', 'notes']);
        });

        // Remove columns from transaction_category table
        Schema::table('transaction_category', function (Blueprint $table) {
            $table->dropColumn(['type', 'color']);
        });

        // Remove columns from people table
        Schema::table('people', function (Blueprint $table) {
            $table->dropColumn(['type', 'address']);
        });
    }
};
