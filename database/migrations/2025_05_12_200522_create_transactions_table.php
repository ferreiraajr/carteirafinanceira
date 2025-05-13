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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('wallet_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('wallet_id_from')->nullable()->constrained('wallets')->onDelete('cascade')->nullable();
            $table->foreignUuid('wallet_id_to')->nullable()->constrained('wallets')->onDelete('cascade')->nullable();
            $table->enum('type', ['deposit', 'withdraw', 'transfer']);
            $table->decimal('amount', 10, 2);
            $table->boolean('reverted')->default(false);
            $table->timestamp('reverted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
