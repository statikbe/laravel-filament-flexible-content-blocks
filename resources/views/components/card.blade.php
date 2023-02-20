@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
@endphp

<div class="relative transition-all duration-300 ease-out bg-white group @if($isFullyClickable()) hover:shadow-md @endif">
    @if(!$slot->isEmpty())
        {{-- Image slot --}}
        {{$slot}}
    @elseif($card->imageHtml)
        {!! $card->imageHtml !!}
    @elseif($card->imageUrl)
        <img src="{{$card->imageUrl}}"
             @if($card->title) alt="{{$card->title}}" @endif />
    @endif

    <div class="p-4 prose sm:p-6 max-w-none">
        @if($card->title)
            <h3>
                @if($getTitleUrl())<a href="{{$getTitleUrl()}}">@endif
                    {{$card->title}}
                @if($getTitleUrl())</a>@endif
            </h3>
        @endif

        @if($card->text)
            <div>{!! $card->text !!}</div>
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
