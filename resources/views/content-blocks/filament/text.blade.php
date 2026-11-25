<div class="{{ $getBackgroundColourClass() }}">
    @if ($title)
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">{{ $replaceParameters($title) }}</h2>
    @endif

    <div class="prose max-w-none dark:prose-invert">
        {!! $replaceParameters($content) !!}
    </div>
</div>
