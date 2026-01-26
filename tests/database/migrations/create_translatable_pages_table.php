<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('translatable_pages', function (Blueprint $table) {
            $table->id();

            // Translatable fields stored as JSON
            $table->json('title');
            $table->json('intro')->nullable();
            $table->json('slug');

            // Content blocks - translatable
            $table->json('content_blocks')->nullable();

            // Hero image attributes - translatable
            $table->json('hero_image_copyright')->nullable();
            $table->json('hero_image_title')->nullable();

            // Publishing
            $table->timestamp('publishing_begins_at')->nullable();
            $table->timestamp('publishing_ends_at')->nullable();

            // SEO - translatable
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();

            // Overview - translatable
            $table->json('overview_title')->nullable();
            $table->json('overview_description')->nullable();

            // Code (non-translatable unique identifier)
            $table->string('code')->unique();

            // Author
            $table->unsignedBigInteger('author_id')->nullable();

            // Parent (for hierarchical pages)
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('translatable_pages');
    }
};
