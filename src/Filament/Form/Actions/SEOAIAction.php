<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SEODescriptionField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SEOKeywordsField;
use Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\SEOTitleField;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasContentBlocks;
use Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasPageAttributes;

class SEOAIAction extends Action
{
    const NAME = 'AIseo';

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
                        'html' => $html,
                    ])->render(),
                ],
            ],
        ];
    }

    private static function invoke(Set $set, Model&HasPageAttributes&HasContentBlocks $record): void
    {
        $title = $record->title;
        $html = $record->getSearchableBlockContent(false);

        try {
            $response = OpenAI::chat()->create(static::getAIParameters($title, $html));
            $result = $response->choices[0]?->message?->content;

            if ($result) {
                $result = json_decode($result);
                if ($result->title) {
                    $set(SEOTitleField::getFieldName(), $result->title);
                }
                if ($result->description) {
                    $set(SEODescriptionField::getFieldName(), $result->description);
                }
                if ($result->tags) {
                    $set(SEOKeywordsField::FIELD, array_map('trim', explode(',', $result->tags) ?? []));
                }

                Notification::make()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.generated_success'))
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.generated_no_response'))
                    ->danger()
                    ->send();
            }
        } catch (\Throwable $t) {
            Log::error($t);
            Notification::make()
                ->title(trans('filament-flexible-content-blocks::filament-flexible-content-blocks.generated_error'))
                ->danger()
                ->send();
        }
    }

    public static function create(): static
    {
        return static::make(self::NAME)
            ->icon('heroicon-o-sparkles')
            ->disabled(function ($record) {
                return ! $record || ! config('openai.api_key');
            })
            ->label(function ($record) {
                if (! $record) {
                    return trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_action.seo_ai_action.name_on_create');
                }

                return trans('filament-flexible-content-blocks::filament-flexible-content-blocks.form_action.seo_ai_action.name');
            })
            ->action(fn (Set $set, Model&HasPageAttributes&HasContentBlocks $record) => static::invoke($set, $record));
    }
}
