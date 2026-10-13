<div class="{{ $getBackgroundColourClass() }}">
    @if ($title)
        <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">{{ $replaceParameters($title) }}</h2>
    @endif

    <div @class([
        'grid grid-cols-1 gap-6 items-start',
        'md:grid-cols-2' => $hasImage() && $imagePosition !== 'center',
    ])>
        <div @class([
            'order-2' => $hasImage() && $imagePosition === 'left',
        ])>
            @if ($text)
                <div class="prose max-w-none dark:prose-invert">
                    {!! $replaceParameters($text) !!}
                </div>
            @endif

            @if ($callToActions)
                <div class="flex flex-wrap items-center gap-3 mt-4">
                    @foreach ($callToActions as $callToAction)
                        <x-flexible-call-to-action :data="$callToAction" />
                    @endforeach
                </div>
            @endif
        </div>

        @if ($hasImage())
            <div>
                {{ $getImageMedia(attributes: ['class' => 'w-full rounded-xl', 'loading' => 'lazy']) }}
                @if ($imageCopyright)
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">&copy; {{ $replaceParameters($imageCopyright) }}</p>
                @endif
            </div>
        @endif
    </div>
</div>
