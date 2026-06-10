@php
/**
 * @deprecated This block has very similar or fewer functionality than other blocks.
 * This block has been removed from the default blocks in the configuration.
 * Use TextImageBlock or CallToActionBlock.
 */
@endphp

<div @class([
    'content-block content-block--text',
    $getBackgroundColourClass(),
])>
    <div class="container">
        <div class="max-w-2xl py-3 text-balance">
            @if ($title)
                <h2>{{ $replaceParameters($title) }}</h2>
            @endif

            <div class="text-base canBeRichEditorContent">
                {!! $replaceParameters($content) !!}
            </div>
        </div>
    </div>
</div>
