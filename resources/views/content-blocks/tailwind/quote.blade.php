<div class="section section--default">
    <div class="container">
        <blockquote @class([
            'pl-6 border-l-2 border-gray-200',
            'grid grid-cols-1 gap-4 md:gap-x-8 justify-items-start items-center',
            'md:grid-cols-2' => $hasImage() && $imagePosition !== 'center',
        ])>
            <div @class([
                'text-balance',
                'order-2' => $hasImage() && $imagePosition === 'left',
            ])>
                <div class="text-xl md:text-2xl">
                    {!! $replaceParameters($quote) !!}
                </div>

                @if ($author)
                    <cite class="text-sm">{{ $replaceParameters($author) }}</cite>
                @endif
            </div>

            @if ($hasImage())
                <div class="text-right">
                    {{ $getImageMedia(attributes: ['loading' => 'lazy']) }}

                    @if ($imageCopyright)
                        <small>&copy; {{ $replaceParameters($imageCopyright) }}</small>
                    @endif
                </div>
            @endif
        </blockquote>
    </div>
</div>
