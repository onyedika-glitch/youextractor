<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'tech_stack')) {
                $table->json('tech_stack')->nullable()->after('code_snippets');
            }
            if (!Schema::hasColumn('videos', 'setup_instructions')) {
                $table->text('setup_instructions')->nullable()->after('tech_stack');
            }
            if (!Schema::hasColumn('videos', 'dependencies')) {
                $table->json('dependencies')->nullable()->after('setup_instructions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['tech_stack', 'setup_instructions', 'dependencies']);
        });
    }
};
