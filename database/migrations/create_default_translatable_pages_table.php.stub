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

            $table->json('title')->default('{}');

            //Publishing:
            $table->timestamp('publishing_begins_at')->nullable();
            $table->timestamp('publishing_ends_at')->nullable();

            //SEO:
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();

            //Overview:
            $table->json('overview_title')->nullable();
            $table->json('overview_description')->nullable();

            //Content blocks:
            $table->json('content_blocks')->default('[]');

            //Slug:
            $table->json('slug')->default('{}');

            $table->timestamps();
        });
    }
};
