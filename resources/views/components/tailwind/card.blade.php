@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
@endphp

<div class="relative transition-all duration-300 ease-out bg-white group card @if($isFullyClickable()) hover:shadow-md @endif">
    @if(!$slot->isEmpty())
        {{-- Image slot --}}
        {{$slot}}
    @elseif($card->hasImage())
        @if($card->imageHtml)
            {!! $card->imageHtml !!}
        @elseif($card->imageUrl)
            <img src="{{$card->imageUrl}}"
                 @if($card->title) alt="{{Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($card->title)}}" @endif class="card__image" />
        @endif
    @endif

    <div class="p-4 prose sm:p-6 max-w-none">
        @if($card->title)
            <h3 class="card__title">
                @if($getTitleUrl())<a href="{{$getTitleUrl()}}">@endif
                    {{$card->title}}
                @if($getTitleUrl())</a>@endif
            </h3>
        @endif

        @if($card->text)
            <div class="card__description">{!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($card->text) !!}</div>
        @endif

        @if($card->callToActions)
            <div class="flex flex-wrap gap-4">
                @foreach($card->callToActions as $callToAction)
                    <x-flexible-call-to-action :data="$callToAction" :isFullyClickable="$isFullyClickable()"></x-flexible-call-to-action>
                @endforeach
            </div>
        @endif
    </div>
</div>
