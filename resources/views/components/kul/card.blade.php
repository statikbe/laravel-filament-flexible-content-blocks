@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
@endphp


<article {{ $attributes->merge(['class' => '']) }}>
    <div class="card card-primary">
        @if ($card->imageUrl)
            <div class="card-img-top" style="background-image: url({{ $card->imageUrl }})"></div>
        @endif
        <div class="card-body">
            @if ($card->title)
                <h3>
                    @if ($getTitleUrl())
                        <a href="{{ $getTitleUrl() }}">
                    @endif
                    {{ $card->title }}
                    @if ($getTitleUrl())
                        </a>
                    @endif
                </h3>
            @endif

            @if ($card->text)
                {!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($card->text) !!}
            @endif

            @if ($card->callToActions)
                <br>
                @foreach ($card->callToActions as $callToAction)
                    <x-flexible-call-to-action :data="$callToAction" :isFullyClickable="$isFullyClickable()"></x-flexible-call-to-action>
                @endforeach
            @endif
        </div>
    </div>
</article>
