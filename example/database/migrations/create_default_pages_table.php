<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            //Intro:
            $table->text('intro')->nullable();

            //Hero image:
            $table->string('hero_image_copyright')->nullable();
            $table->string('hero_image_title')->nullable();

            //Publishing:
            $table->timestamp('publishing_begins_at')->nullable();
            $table->timestamp('publishing_ends_at')->nullable();

            //SEO:
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();

            //Overview:
            $table->string('overview_title')->nullable();
            $table->text('overview_description')->nullable();

            //Content blocks:
            $table->json('content_blocks')->default('[]'); //Default only works on JSON on MySQL 8 or newer

            //Slug:
            $table->string('slug');

            //Author:
            $table->unsignedBigInteger('author_id')->nullable();
            $table->foreign('author_id')
                ->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }
};
