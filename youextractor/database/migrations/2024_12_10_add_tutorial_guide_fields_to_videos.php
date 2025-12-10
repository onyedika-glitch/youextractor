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
            $table->json('tutorial_guide')->nullable()->after('dependencies');
            $table->json('ide_recommendations')->nullable()->after('tutorial_guide');
            $table->json('prerequisites')->nullable()->after('ide_recommendations');
            $table->json('setup_guide')->nullable()->after('prerequisites');
            $table->json('run_guide')->nullable()->after('setup_guide');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['tutorial_guide', 'ide_recommendations', 'prerequisites', 'setup_guide', 'run_guide']);
        });
    }
};
