<div>
    @if($title)
        <h2>{{$title}}</h2>
    @endif
    <div class="grid grid-cols-{{$gridColumns ?? 3}} gap-4">
        @foreach($cards as $card)
            @php
                /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
            @endphp

            <x-flexible-card :title="$card->title"
                             :text="$card->text"
                             :call-to-actions="$card->callToActions"
                             :image="$getCardImageMedia($card->imageId, $card->title)">
            </x-flexible-card>
        @endforeach
    </div>
</div>
