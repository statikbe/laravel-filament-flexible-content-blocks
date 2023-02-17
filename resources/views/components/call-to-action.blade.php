@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData $callToAction */
@endphp

<div {{$attributes}}>
    <a href="{{$callToAction->url}}"
    @if($callToAction->label) title="{{$callToAction->label}}" @endif
    class="@if($callToAction->buttonStyle) {{$callToAction->buttonStyle}} before:absolute before:inset-0 @endif"
    @if($callToAction->openNewWindow) target="_blank" rel="noopener noreferrer" @endif>
        @if($callToAction->label)
            {{$callToAction->label}}
        @else
            &xrarr;
        @endif
    </a>
</div>
