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
            'title' => ['en' => $titleEn, 'nl' => $titleNl],
            'slug' => ['en' => Str::slug($titleEn), 'nl' => Str::slug($titleNl)],
            'intro' => ['en' => '<p>'.$this->faker->paragraph().'</p>', 'nl' => '<p>'.$this->faker->paragraph().'</p>'],
            'hero_image_copyright' => ['en' => $this->faker->name(), 'nl' => $this->faker->name()],
            'hero_image_title' => ['en' => $this->faker->sentence(3), 'nl' => $this->faker->sentence(3)],
            'publishing_begins_at' => now()->subDay(),
            'publishing_ends_at' => null,
            'seo_title' => ['en' => $this->faker->sentence(6), 'nl' => $this->faker->sentence(6)],
            'seo_description' => ['en' => $this->faker->paragraph(), 'nl' => $this->faker->paragraph()],
            'seo_keywords' => ['en' => [$this->faker->word(), $this->faker->word()], 'nl' => [$this->faker->word(), $this->faker->word()]],
            'overview_title' => ['en' => $this->faker->sentence(4), 'nl' => $this->faker->sentence(4)],
            'overview_description' => ['en' => '<p>'.$this->faker->paragraph().'</p>', 'nl' => '<p>'.$this->faker->paragraph().'</p>'],
            'content_blocks' => [],
            'code' => $this->faker->unique()->word(),
            'author_id' => null,
            'parent_id' => null,
        ];
    }

    public function withContentBlocks(array $blocks = []): static
    {
        return $this->state(fn (array $attributes) => [
            'content_blocks' => $blocks,
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
