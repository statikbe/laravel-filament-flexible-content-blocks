@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData $callToAction */
@endphp


<a class="btn @if($callToAction->buttonStyle) {{$callToAction->buttonStyle}} @endif"
   href="{{$callToAction->url}}"
    @if($callToAction->label) title="{{Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($callToAction->label)}}" @endif
    @if($callToAction->openNewWindow) target="_blank" rel="noopener noreferrer" @endif>
        @if($callToAction->label)
            {{Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($callToAction->label)}}
        @else
            &xrarr;
        @endif
</a>
