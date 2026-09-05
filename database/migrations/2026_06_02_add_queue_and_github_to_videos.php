<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds columns to support:
 *  - Background queue job tracking (extraction_status, extraction_error)
 *  - GitHub push integration (github_repo_url)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'extraction_status')) {
                // pending | processing | completed | failed
                $table->string('extraction_status')->default('completed')->after('extracted_at');
            }
            if (!Schema::hasColumn('videos', 'extraction_error')) {
                $table->text('extraction_error')->nullable()->after('extraction_status');
            }
            if (!Schema::hasColumn('videos', 'github_repo_url')) {
                $table->string('github_repo_url')->nullable()->after('extraction_error');
            }
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['extraction_status', 'extraction_error', 'github_repo_url']);
        });
    }
};
