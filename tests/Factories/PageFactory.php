<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    protected $model = \Statikbe\FilamentFlexibleContentBlocks\Tests\Models\Page::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'intro' => $this->faker->paragraph(),
            'hero_image_copyright' => $this->faker->name(),
            'hero_image_title' => $this->faker->sentence(3),
            'publishing_begins_at' => now()->subDay(),
            'publishing_ends_at' => null,
            'seo_title' => $this->faker->sentence(6),
            'seo_description' => $this->faker->paragraph(),
            'seo_keywords' => json_encode([$this->faker->word(), $this->faker->word()]),
            'overview_title' => $this->faker->sentence(4),
            'overview_description' => $this->faker->paragraph(),
            'content_blocks' => json_encode([]),
            'author_id' => null,
            'parent_id' => null,
        ];
    }

    public function withContentBlocks(array $blocks = []): static
    {
        return $this->state(fn (array $attributes) => [
            'content_blocks' => json_encode($blocks),
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'publishing_begins_at' => now()->subDay(),
            'publishing_ends_at' => null,
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'publishing_begins_at' => now()->addDay(),
            'publishing_ends_at' => null,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'publishing_begins_at' => now()->subWeek(),
            'publishing_ends_at' => now()->subDay(),
        ]);
    }
}
