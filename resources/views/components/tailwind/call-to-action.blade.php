@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData $callToAction */
@endphp

@if($callToAction->url)
    <div {{$attributes}}>
        <a href="{{$callToAction->url}}"
            @if($callToAction->label) title="{{Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($callToAction->label)}}" @endif
            class="
                @if($callToAction->buttonStyle) {{$callToAction->buttonStyle}} @endif  
                @if($isFullyClickable) before:absolute before:inset-0 @endif
                @if($callToAction->icon) flex {{$callToAction->icon_position}} @endif
                "
            @if($callToAction->openNewWindow) target="_blank" rel="noopener noreferrer" @endif
            >
                @if($callToAction->label)
                    {{Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($callToAction->label)}}
                @else
                    &xrarr;
                @endif

                 @if($callToAction->icon)   
                    <i class="{{$callToAction->icon}}" aria-hidden="true"></i>
                @endif
        </a>
    </div>
@endif
