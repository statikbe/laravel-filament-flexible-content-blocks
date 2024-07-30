<section @class([
    'card tw-relative',
    $hasImage() ? 'card-cover card-inverse' : 'card-gray',
    $getBackgroundColourClass(),
])>

    @if ($hasImage())
        <div class="card-img" style="background-image: url({{ $getImageUrl() }})"></div>
    @endif

    <div @class([
        $hasImage()
            ? 'card-img-overlay tw-bg-gradient-to-t tw-from-black'
            : 'card-body',
    ])>

        @if ($title)
            <h2>{{ $replaceParameters($title) }}</h2>
        @endif

        @if ($text)
            <div>
                {!! $replaceParameters($text) !!}
            </div>
        @endif

        @if ($callToActions)<br>
            @if (count($callToActions) == 1)
                <p>
                    <x-flexible-call-to-action :data="$callToActions[0]"></x-flexible-call-to-action>
                </p>
            @else
                <div class="inline">
                    @foreach ($callToActions as $callToAction)
                        <div class="inline-item">
                            <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
        @if ($imageCopyright)
            <small class="tw-absolute tw-right-0 tw-bottom-0 tw-bg-black tw-text-white tw-px-1">&copy;
                {{ $replaceParameters($imageCopyright) }}</small>
        @endif
    </div>
</section>
