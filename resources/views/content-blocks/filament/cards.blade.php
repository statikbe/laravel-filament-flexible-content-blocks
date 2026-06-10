@php
    /* Build the grid column classes as complete literals so Tailwind can detect
       them — a concatenated `md:grid-cols-{$n}` string is never generated. */
    $gridColsClass = match ((int) $gridColumns) {
        2 => 'sm:grid-cols-2',
        3 => 'sm:grid-cols-2 lg:grid-cols-3',
        4 => 'sm:grid-cols-2 lg:grid-cols-4',
        default => '',
    };
@endphp

<div class="{{ $getBackgroundColourClass() }}">
    @if ($title)
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">{{ $replaceParameters($title) }}</h2>
    @endif

    <div @class(['grid grid-cols-1 gap-6', $gridColsClass => $gridColsClass !== ''])>
        @foreach ($cards as $card)
            @php
                /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
            @endphp

            <x-flexible-card :data="$card">
                {!! $getCardImageMedia($card->cardId, $card->title, false, ['class' => 'w-full rounded-lg']) !!}
            </x-flexible-card>
        @endforeach
    </div>
</div>
