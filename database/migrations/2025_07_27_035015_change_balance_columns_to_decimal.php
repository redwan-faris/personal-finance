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
        // Change people.balance from integer to decimal
        Schema::table('people', function (Blueprint $table) {
            $table->decimal('balance', 15, 2)->default(0)->change();
        });

        // Change wallets.balance from integer to decimal
        Schema::table('wallets', function (Blueprint $table) {
            $table->decimal('balance', 15, 2)->default(0)->change();
        });

        // Change transactions.amount from integer to decimal
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert people.balance back to integer
        Schema::table('people', function (Blueprint $table) {
            $table->integer('balance')->default(0)->change();
        });

        // Revert wallets.balance back to integer
        Schema::table('wallets', function (Blueprint $table) {
            $table->integer('balance')->default(0)->change();
        });

        // Revert transactions.amount back to integer
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('amount')->change();
        });
    }
};
