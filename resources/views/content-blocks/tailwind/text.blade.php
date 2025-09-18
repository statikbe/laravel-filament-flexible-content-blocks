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
