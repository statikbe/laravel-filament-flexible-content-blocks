@if ($content)
    <div class="container {{ $getBackgroundColourClass() }}">
        <section class="max-w-2xl py-3 text-balance">
            @if ($title)
                <h2>{{ $replaceParameters($title) }}</h2>
            @endif

            <div class="text-base">
                {!! $replaceParameters($content) !!}
            </div>
        </section>
    </div>
@endif
