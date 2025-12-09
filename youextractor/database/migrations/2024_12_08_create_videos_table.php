<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_id')->unique();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->longText('transcript')->nullable();
            $table->longText('explanation');
            $table->json('code_snippets')->nullable();
            $table->longText('summary');
            $table->integer('duration')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('extracted_at');
            $table->timestamps();
            
            $table->index('youtube_id');
            $table->index('extracted_at');
            $table->fullText(['title', 'explanation', 'summary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
