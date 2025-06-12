<div class="container {{ $getBackgroundColourClass() }}">
    <section @class([
        'py-3',
        'grid grid-cols-1 gap-4 md:gap-x-8 justify-items-start items-center',
        'md:grid-cols-2' => $hasImage() && $imagePosition !== 'center',
    ])>
        <div @class([
            'max-w-2xl text-balance',
            'order-2' => $hasImage() && $imagePosition === 'left',
        ])>
            @if ($title)
                <h2>{{ $replaceParameters($title) }}</h2>
            @endif
            @if ($text)
                <div class="text-base">
                    {!! $replaceParameters($text) !!}
                </div>
            @endif
            @if ($callToActions)
                <div class="flex flex-wrap items-center gap-4 mt-6">
                    @foreach ($callToActions as $callToAction)
                        <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                    @endforeach
                </div>
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
    </section>
</div>
