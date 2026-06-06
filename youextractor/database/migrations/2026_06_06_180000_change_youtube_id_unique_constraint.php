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
        Schema::table('videos', function (Blueprint $table) {
            // Drop the old global unique constraint on youtube_id
            $table->dropUnique(['youtube_id']);
            
            // Add a composite unique constraint for user_id and youtube_id
            $table->unique(['user_id', 'youtube_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            // Drop the composite constraint
            $table->dropUnique(['user_id', 'youtube_id']);
            
            // Re-add the global unique constraint on youtube_id
            $table->unique('youtube_id');
        });
    }
};
