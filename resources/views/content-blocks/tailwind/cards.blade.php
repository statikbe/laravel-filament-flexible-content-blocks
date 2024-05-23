<div class="section {{ $getBackgroundColourClass() }}">
    <div class="container">
        @if($title)
            <h2>{{$replaceParameters($title)}}</h2>
        @endif
        <div @class(['grid gap-4 ', 'sm:grid-cols-2 md:grid-cols-' . $gridColumns => $gridColumns > 1])>
            @foreach($cards as $card)
                @php
                     /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
                @endphp

                <x-flexible-card :data="$card">
                    {!! $getCardImageMedia($card->imageId, $card->title, false, ['class' => 'w-full']) !!}
                </x-flexible-card>
            @endforeach
        </div>
    </div>
</div>
