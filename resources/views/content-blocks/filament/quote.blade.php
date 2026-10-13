<div class="{{ $getBackgroundColourClass() }}">
    <div @class([
        'grid grid-cols-1 gap-6 items-center',
        'md:grid-cols-2' => $hasImage() && $imagePosition !== 'center',
    ])>
        <blockquote @class([
            'border-l-4 border-primary-500 pl-6 py-2',
            'order-2' => $hasImage() && $imagePosition === 'left',
        ])>
            <div class="text-xl italic text-gray-700 dark:text-gray-300">
                {!! $replaceParameters($quote) !!}
            </div>

            @if ($author)
                <cite class="mt-3 block text-sm font-medium text-gray-500 not-italic dark:text-gray-400">
                    &mdash; {{ $replaceParameters($author) }}
                </cite>
            @endif
        </blockquote>

        @if ($hasImage())
            <div>
                {{ $getImageMedia(attributes: ['class' => 'aspect-video w-full rounded-xl object-cover', 'loading' => 'lazy']) }}
                @if ($imageCopyright)
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">&copy; {{ $replaceParameters($imageCopyright) }}</p>
                @endif
            </div>
        @endif
    </div>
</div>
