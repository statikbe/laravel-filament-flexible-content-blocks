@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CallToActionData $callToAction */
    $label = $callToAction->label
        ? Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($callToAction->label)
        : null;
    $isLink = str_contains($callToAction->buttonStyle ?? '', 'link');
@endphp

@if($callToAction->url)
    @if ($isLink)
        <x-filament::link
            :href="$callToAction->url"
            :target="$callToAction->openNewWindow ? '_blank' : null"
            :rel="$callToAction->openNewWindow ? 'noopener noreferrer' : null"
            :title="$label"
            :class="$isFullyClickable ? 'before:absolute before:inset-0' : null"
        >
            {{ $label ?? '→' }}
        </x-filament::link>
    @else
        <x-filament::button
            tag="a"
            :href="$callToAction->url"
            :target="$callToAction->openNewWindow ? '_blank' : null"
            :rel="$callToAction->openNewWindow ? 'noopener noreferrer' : null"
            :title="$label"
            :color="str_contains($callToAction->buttonStyle ?? '', 'secondary') ? 'gray' : 'primary'"
            :outlined="str_contains($callToAction->buttonStyle ?? '', 'ghost')"
            :class="$isFullyClickable ? 'before:absolute before:inset-0' : null"
        >
            {{ $label ?? '→' }}
        </x-filament::button>
    @endif
@endif
