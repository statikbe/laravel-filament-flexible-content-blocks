@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
@endphp

<article class="tw-relative tw-transition tw-duration-300 tw-ease-out tw-bg-white tw-group @if($isFullyClickable()) hover:tw-shadow-md @endif">
    @if(!$slot->isEmpty())
        {{-- Image slot --}}
        {{ $slot }}
    @elseif($card->hasImage())
        @if($card->imageHtml)
            {!! $card->imageHtml !!}
        @elseif($card->imageUrl)
            <img src="{{ $card->imageUrl }}" class="tw-w-full"
                 @if($card->title)alt="{{Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($card->title)}}"@endif/>
        @endif
    @endif

    <div class="tw-p-4 sm:tw-p-6">
        @if($card->title)
            <h3>
                @if($getTitleUrl())<a href="{{$getTitleUrl()}}">@endif
                    {{ $card->title }}
                @if($getTitleUrl())</a>@endif
            </h3>
        @endif

        @if($card->text)
            <div>{!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($card->text) !!}</div>
        @endif

        @if($card->callToActions)
            <div class="tw-flex tw-flex-wrap tw-gap-4 tw-mt-4">
                @foreach($card->callToActions as $callToAction)
                    <x-flexible-call-to-action :data="$callToAction" :isFullyClickable="$isFullyClickable()"></x-flexible-call-to-action>
                @endforeach
            </div>
        @endif
    </div>
</article>
