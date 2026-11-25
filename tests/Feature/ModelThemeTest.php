<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\View\Component;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\AbstractContentBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\CardsBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\HtmlBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\OverviewBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextBlock;
use Statikbe\FilamentFlexibleContentBlocks\ContentBlocks\TextImageBlock;
use Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleBlocksConfig;
use Statikbe\FilamentFlexibleContentBlocks\Tests\Models\TranslatablePage;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\ContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\View\Components\Hero;

function pageWithTheme(?string $theme): TranslatablePage
{
    $page = new class extends TranslatablePage
    {
        public ?string $theme = null;

        public function getContentBlocksTheme(): ?string
        {
            return $this->theme;
        }
    };
    $page->theme = $theme;

    return $page;
}

beforeEach(function () {
    setupDefaultContentBlocks();
    config(['filament-flexible-content-blocks.theme' => 'tailwind']);
});

it('returns the config theme when the model has no theme hook', function () {
    $page = new TranslatablePage;

    expect(FilamentFlexibleBlocksConfig::getViewThemePrefix($page))
        ->toBe(FilamentFlexibleBlocksConfig::getViewThemePrefix())
        ->toBe('tailwind.');
});

it('returns the model theme when the model defines one', function () {
    expect(FilamentFlexibleBlocksConfig::getViewThemePrefix(pageWithTheme('filament')))->toBe('filament.');
});

it('ignores a null or empty model theme', function (?string $theme) {
    expect(FilamentFlexibleBlocksConfig::getViewThemePrefix(pageWithTheme($theme)))->toBe('tailwind.');
})->with([null, '']);

it('renders the content blocks component in the model theme', function () {
    $component = new ContentBlocks(pageWithTheme('filament'));

    expect($component->render()->name())
        ->toBe('filament-flexible-content-blocks::components.filament.content-blocks');
});

it('renders a block in the model theme', function () {
    $block = new HtmlBlock(pageWithTheme('filament'), ['html' => '<p>x</p>']);

    expect($block->render()->name())->toEndWith('content-blocks.filament.html');
});

/**
 * Renders the view and returns the names of all views that were created while doing so.
 */
function renderedViewNames(Component $component): array
{
    $names = [];
    View::creator('*', function ($created) use (&$names) {
        $names[] = $created->name();
    });
    Blade::renderComponent($component);

    return $names;
}

function expectOnlyModelThemeNested(array $names, string $nestedSuffix): void
{
    $nested = array_values(array_filter($names, fn ($name) => str_contains($name, '::components.')));

    expect($nested)->toContain('filament-flexible-content-blocks::components.filament.'.$nestedSuffix)
        ->and(array_filter($nested, fn ($name) => str_contains($name, '.tailwind.')))->toBeEmpty();
}

function ctaData(): array
{
    return [[
        'cta_model' => 'url',
        'url' => 'https://example.com',
        'button_label' => 'Go',
    ]];
}

it('renders the call to action of a block in the model theme', function () {
    $block = new TextImageBlock(pageWithTheme('filament'), [
        'text' => '<p>x</p>',
        'call_to_action' => ctaData(),
    ]);

    expectOnlyModelThemeNested(renderedViewNames($block), 'call-to-action');
});

it('renders the cards and their call to actions in the model theme', function () {
    $block = new CardsBlock(pageWithTheme('filament'), [
        'grid_columns' => 3,
        'cards' => [['title' => 'Card', 'text' => 'x', 'card_call_to_action' => ctaData()]],
    ]);

    $names = renderedViewNames($block);

    expectOnlyModelThemeNested($names, 'card');
    expect($names)->toContain('filament-flexible-content-blocks::components.filament.call-to-action');
});

it('renders the overview cards in the model theme', function () {
    $item = TranslatablePage::factory()->create();
    config(['filament-flexible-content-blocks.overview_models' => [TranslatablePage::class]]);
    $block = new OverviewBlock(pageWithTheme('filament'), [
        'grid_columns' => 3,
        'items' => [['overview_model' => $item->getMorphClass(), 'overview_id' => $item->id]],
    ]);

    expectOnlyModelThemeNested(renderedViewNames($block), 'overview-card');
});

it('renders the hero call to actions and background video in the model theme', function () {
    $page = new class extends TranslatablePage
    {
        public function getContentBlocksTheme(): ?string
        {
            return 'filament';
        }

        // Hero leaves these properties uninitialised when empty, which breaks rendering outside a real page.
        public function getTitle(): ?string
        {
            return 'Title';
        }

        public function getIntro(): ?string
        {
            return 'Intro';
        }

        public function getHeroImageTitle(): ?string
        {
            return 'Image';
        }

        public function getHeroImageCopyright(): ?string
        {
            return 'Copyright';
        }

        public function hasHeroVideoUrl(): bool
        {
            return true;
        }

        public function getHeroVideoUrl(): ?string
        {
            return 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
        }
    };
    $page->hero_call_to_actions = ctaData();

    $names = renderedViewNames(new Hero($page));

    expectOnlyModelThemeNested($names, 'call-to-action');
    expect($names)->toContain('filament-flexible-content-blocks::components.filament.background-video');
});

it('uses the model theme in the editor preview', function () {
    expect(AbstractContentBlock::getPreviewViewForModel(pageWithTheme('filament')))
        ->toBe('filament-flexible-content-blocks::content-blocks.filament.preview');
});

it('keeps the argument-less getPreviewView on the config theme', function () {
    expect(AbstractContentBlock::getPreviewView())
        ->toBe('filament-flexible-content-blocks::content-blocks.tailwind.preview');
});

it('uses the scoped model theme without an explicit model and restores it afterwards', function () {
    $inside = FilamentFlexibleBlocksConfig::withTheme(pageWithTheme('filament'), fn () => [
        FilamentFlexibleBlocksConfig::getViewThemePrefix(),
        FilamentFlexibleBlocksConfig::withTheme(pageWithTheme('bootstrap4-kul'), fn () => FilamentFlexibleBlocksConfig::getViewThemePrefix()),
        FilamentFlexibleBlocksConfig::getViewThemePrefix(),
    ]);

    expect($inside)->toBe(['filament.', 'bootstrap4-kul.', 'filament.'])
        ->and(FilamentFlexibleBlocksConfig::getViewThemePrefix())->toBe('tailwind.');
});

it('restores the theme scope when the callback throws', function () {
    try {
        FilamentFlexibleBlocksConfig::withTheme(pageWithTheme('filament'), fn () => throw new RuntimeException);
    } catch (RuntimeException) {
    }

    expect(FilamentFlexibleBlocksConfig::getViewThemePrefix())->toBe('tailwind.');
});

it('renders a text block in the filament theme', function () {
    $block = new TextBlock(pageWithTheme('filament'), [
        'title' => 'A <b>title</b>',
        'content' => '<p>Some content</p>',
    ]);

    $view = $block->render();
    $html = Blade::renderComponent($block);

    expect($view->name())->toBe('filament-flexible-content-blocks::content-blocks.filament.text')
        ->and($html)->toContain('A &lt;b&gt;title&lt;/b&gt;')
        ->and($html)->toContain('<p>Some content</p>');
});
