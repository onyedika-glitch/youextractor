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
        // 1. Add credit & extraction tracking to users table
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'free_extractions_used')) {
                $table->integer('free_extractions_used')->default(0)->after('email');
            }
            if (! Schema::hasColumn('users', 'credits')) {
                $table->integer('credits')->default(0)->after('free_extractions_used');
            }
            if (! Schema::hasColumn('users', 'is_pro')) {
                $table->boolean('is_pro')->default(false)->after('credits');
            }
            if (! Schema::hasColumn('users', 'pro_until')) {
                $table->timestamp('pro_until')->nullable()->after('is_pro');
            }
        });

        // 2. Create payments table for Bachs transactions
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('reference')->unique();
                $table->string('checkout_id')->nullable()->index();
                $table->decimal('amount', 10, 2);
                $table->string('currency', 10)->default('USD');
                $table->string('plan_type')->default('starter_credits'); // starter_credits (5), pro_credits (20), monthly_pro
                $table->integer('credits_added')->default(0);
                $table->string('status')->default('pending'); // pending, completed, failed
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['free_extractions_used', 'credits', 'is_pro', 'pro_until']);
        });
    }
};
