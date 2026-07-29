<div @class([
    'content-block content-block--cards',
    $getBackgroundColourClass(),
])>
    <div class="container">
        <div class="flex flex-col items-start gap-3 md:gap-6">
            @if ($title)
                <h2 class="text-balance">{{ $replaceParameters($title) }}</h2>
            @endif

            @php
                $nrOfItems = count($cards);
                $actualNrGridCols = min($gridColumns, $nrOfItems);

                $isCol2 = $actualNrGridCols >= 2;
                $isCol3 = $actualNrGridCols >= 3;
                $isCol4 = $actualNrGridCols === 4;
            @endphp

            <ul @class([
                'grid gap-x-6 gap-y-4 sm:gap-y-6',
                'sm:grid-cols-2' => $isCol2,
                'lg:grid-cols-3' => $isCol3,
                'xl:grid-cols-4' => $isCol4,
            ])>
                @foreach ($cards as $card)
                    @php
                        /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
                    @endphp

                    <li @class([
                        'border border-gray-300 rounded-xl overflow-clip',
                        'max-w-md' => count($cards) === 1,
                    ])>>
                        <x-flexible-card :data="$card">
                            {!! $getCardImageMedia($card->cardId, $card->title, false, ['class' => 'w-full']) !!}
                        </x-flexible-card>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
