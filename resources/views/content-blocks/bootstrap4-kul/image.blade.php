<section id="image" class="card {{ $getBackgroundColourClass() }} tw-w-full">
    <div @class([
        'tw-flex',
        'tw-justify-start' => $imagePosition === 'left',
        'tw-justify-center' => $imagePosition === 'center',
        'tw-justify-end' => $imagePosition === 'right',
    ])>
        {{ $getImageMedia(attributes: [
            'alt' => $imageTitle, 
            'loading' => 'lazy', 
            'class' => $getImageWidthClass()
        ]) }}
        @if ($imageCopyright)
            <small 
                class="tw-absolute tw-bottom-0 tw-bg-black/80 tw-text-white tw-px-1"
            >
                &copy; {{ $replaceParameters($imageCopyright) }}
            </small>
        @endif
    </div>
</section>