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

            $table->json('title')->default('{}'); //Default only works on JSON on MySQL 8 or newer

            //Intro:
            $table->json('intro')->default('{}');

            //Hero image:
            $table->json('hero_image_copyright')->default('{}');
            $table->json('hero_image_title')->default('{}');

            //Publishing:
            $table->timestamp('publishing_begins_at')->nullable();
            $table->timestamp('publishing_ends_at')->nullable();
            $table->index('publishing_begins_at');
            $table->index('publishing_ends_at');

            //SEO:
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();

            //Overview:
            $table->json('overview_title')->nullable();
            $table->json('overview_description')->nullable();

            //Content blocks:
            $table->json('content_blocks')->default('[]'); //Default only works on JSON on MySQL 8 or newer

            //Slug:
            $table->json('slug')->default('{}');

            //Unique code:
            $table->string('code')->nullable()->unique();

            //Author:
            $table->unsignedBigInteger('author_id')->nullable();
            $table->foreign('author_id')
                ->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }
};
