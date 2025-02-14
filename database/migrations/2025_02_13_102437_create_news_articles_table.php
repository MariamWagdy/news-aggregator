<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();  //table pk
            $table->string('source_article_id')->nullable(); //article ID from the API
            $table->string('title');
            $table->string('source')->nullable();
            $table->foreignId('platform')->constrained('news_platforms');
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('url')->unique();
            $table->string('image_url')->nullable();
            $table->foreignId('category')->constrained('news_categories');
            $table->timestamp('published_at')->nullable();
            $table->timestamps(); //record creation date

            // Add unique index
            $table->unique('source_article_id', 'idx_unique_source_article_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};
