<section class="section {{ $getBackgroundColourClass() }}">
    <div class="tw-container">
        @if($title)
            <h2>{{$replaceParameters($title)}}</h2>
        @endif
        <div @class(['tw-grid tw-gap-4 ', 'sm:tw-grid-cols-2 md:tw-grid-cols-' . $gridColumns => $gridColumns > 1])>
            @foreach($cards as $card)
                @php
                     /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
                @endphp

                <x-flexible-card :data="$card">
                    {!! $getCardImageMedia($card->imageId, $card->title, false, ['class' => 'tw-w-full']) !!}
                </x-flexible-card>
            @endforeach
        </div>
    </div>
</section>
