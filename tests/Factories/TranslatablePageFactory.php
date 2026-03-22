<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\TranslatablePage;

class TranslatablePageFactory extends Factory
{
    protected $model = TranslatablePage::class;

    public function definition(): array
    {
        $titleEn = $this->faker->sentence(4);
        $titleNl = $this->faker->sentence(4);

        return [
            'title' => json_encode(['en' => $titleEn, 'nl' => $titleNl]),
            'slug' => json_encode(['en' => Str::slug($titleEn), 'nl' => Str::slug($titleNl)]),
            'intro' => json_encode(['en' => $this->faker->paragraph(), 'nl' => $this->faker->paragraph()]),
            'hero_image_copyright' => json_encode(['en' => $this->faker->name(), 'nl' => $this->faker->name()]),
            'hero_image_title' => json_encode(['en' => $this->faker->sentence(3), 'nl' => $this->faker->sentence(3)]),
            'publishing_begins_at' => now()->subDay(),
            'publishing_ends_at' => null,
            'seo_title' => json_encode(['en' => $this->faker->sentence(6), 'nl' => $this->faker->sentence(6)]),
            'seo_description' => json_encode(['en' => $this->faker->paragraph(), 'nl' => $this->faker->paragraph()]),
            'seo_keywords' => json_encode(['en' => [$this->faker->word(), $this->faker->word()], 'nl' => [$this->faker->word(), $this->faker->word()]]),
            'overview_title' => json_encode(['en' => $this->faker->sentence(4), 'nl' => $this->faker->sentence(4)]),
            'overview_description' => json_encode(['en' => $this->faker->paragraph(), 'nl' => $this->faker->paragraph()]),
            'content_blocks' => json_encode([]),
            'code' => $this->faker->unique()->word(),
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
}
