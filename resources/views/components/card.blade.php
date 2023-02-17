@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
@endphp

<div class="card">
    @if(!$slot->isEmpty())
        {{-- Image slot --}}
        {{$slot}}
    @elseif($card->imageHtml)
        {!! $card->imageHtml !!}
    @elseif($card->imageUrl)
        <img src="{{$card->imageUrl}}"
             @if($card->title) alt="{{$card->title}}" @endif />
    @endif

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
        @foreach($card->callToActions as $callToAction)
            <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
        @endforeach
    @endif
</div>
