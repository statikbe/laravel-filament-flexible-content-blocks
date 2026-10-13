@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
@endphp

<x-filament::section @class(['relative h-full', 'transition duration-300 ease-out hover:shadow-md' => $isFullyClickable()])>
    @if ($card->title)
        <x-slot name="heading">
            @if ($getTitleUrl())
                <a href="{{ $getTitleUrl() }}" class="hover:underline">{{ $card->title }}</a>
            @else
                {{ $card->title }}
            @endif
        </x-slot>
    @endif

    @if (!$slot->isEmpty())
        {{-- Image slot --}}
        <div class="mb-4">{{ $slot }}</div>
    @elseif($card->hasImage())
        @if ($card->imageHtml)
            <div class="mb-4">{!! $card->imageHtml !!}</div>
        @elseif($card->imageUrl)
            <img src="{{ $card->imageUrl }}" class="mb-4 w-full rounded-lg"
                 @if ($card->title) alt="{{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($card->title) }}" @endif />
        @endif
    @endif

    @if ($card->text)
        <div class="prose max-w-none dark:prose-invert">
            {!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($card->text) !!}
        </div>
    @endif

    @if ($card->callToActions)
        <div class="mt-4 flex flex-wrap gap-3">
            @foreach ($card->callToActions as $callToAction)
                <x-flexible-call-to-action :data="$callToAction" :isFullyClickable="$isFullyClickable()"></x-flexible-call-to-action>
            @endforeach
        </div>
    @endif
</x-filament::section>
