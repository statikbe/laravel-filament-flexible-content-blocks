@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData $callToAction */
@endphp


<a 
    @class([
        'btn', 
        isset($callToAction->buttonStyle) => $callToAction->buttonStyle
    ])
    href="{{ $callToAction->url }}"
    @if (isset($callToAction->label))
        title="{{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}" 
    @endif
    @if (isset($callToAction->openNewWindow)) 
        target="_blank" rel="noopener noreferrer" 
    @endif
>
    @if (isset($callToAction->label))
        {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($callToAction->label) }}
    @else
        &xrarr;
    @endif
</a>
