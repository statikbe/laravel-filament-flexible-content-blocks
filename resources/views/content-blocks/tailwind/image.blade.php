<div
    aria-hidden="true"
    @class([
        'content-block content-block--image',
        $getBackgroundColourClass(),
    ])
>
    <div class="container">
        <div @class([
            'w-full',
            $getImageWidthClass(),
            '' => $imagePosition === 'left',
            'mx-auto text-right' => $imagePosition === 'center',
            'ml-auto text-right' => $imagePosition === 'right',
        ])>
            {{ $getImageMedia(attributes: [
                'alt' => $imageTitle,
                'class' => 'w-full',
                'loading' => 'lazy',
            ]) }}

            @if ($imageCopyright)
                <small>&copy; {{ $replaceParameters($imageCopyright) }}</small>
            @endif
        </div>
    </div>
</div>
