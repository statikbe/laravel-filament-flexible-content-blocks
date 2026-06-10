<div class="{{ $getBackgroundColourClass() }}">
    <div @class([
        $getImageWidthClass(),
        'mr-auto' => $imagePosition === 'left',
        'mx-auto' => $imagePosition === 'center',
        'ml-auto' => $imagePosition === 'right',
    ])>
        {{ $getImageMedia(attributes: ['alt' => $imageTitle, 'class' => 'w-full rounded-xl', 'loading' => 'lazy']) }}
        @if ($imageCopyright)
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">&copy; {{ $replaceParameters($imageCopyright) }}</p>
        @endif
    </div>
</div>
