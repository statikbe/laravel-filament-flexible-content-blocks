<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Actions;

use App\Models\TranslatablePage;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use OpenAI\Laravel\Facades\OpenAI;

class SEOAIAction extends Actions
{
    private static function getAIParameters($title, $html): array
    {
        return [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => view('filament-flexible-content-blocks::ai.system-prompt')->render(),
                ],
                [
                    'role' => 'user',
                    'content' => view('filament-flexible-content-blocks::ai.user-prompt', [
                        'title' => $title,
                        'html' => $html
                    ])->render()
                ],
            ],
        ];
    }

    private static function invoke(Set $set, TranslatablePage $page): void
    {
        $title = $page->title;
        $html = $page->getSearchableBlockContent(false);

        try {
            $response = OpenAI::chat()->create(static::getAIParameters($title, $html));
            $result = $response->choices[0]?->message?->content;

            if ($result) {
                $result = json_decode($result);
                $set('seo_title', $result->title);
                $set('seo_description', $result->description);
                $set('seo_keywords', array_map('trim', explode(',', $result->tags) ?? []));

                Notification::make()
                    ->title('Generated SEO fields using AI!')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('No response from AI..')
                    ->danger()
                    ->send();
            }
        } catch (\Throwable) {
            Notification::make()
                ->title('Something went wrong, sorry :(')
                ->danger()
                ->send();
        }
    }

    public static function create(): static
    {
        return static::make([
            Action::make('AIseo')
                ->icon('heroicon-o-sparkles')
                ->disabled(function ($record) {
                    return !$record || !config('openai.api_key');
                })
                ->label(function ($record) {
                    if (!$record) {
                        return 'AI-ify (only on edit)';
                    }
                    return 'AI-ify';
                })
                ->action(fn(Set $set, TranslatablePage $page) => static::invoke($set, $page))
        ]);
    }
}
