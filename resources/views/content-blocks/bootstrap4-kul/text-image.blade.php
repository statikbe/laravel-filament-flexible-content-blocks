<section @class([
    'card',
    $getBackgroundColourClass(),
    'card-50-50' => $hasImage() && $imagePosition == 'left',
    'card-50-50-right' => $hasImage() && $imagePosition == 'right',
])>
    @if ($hasImage())
        <div @class([
            $imagePosition === 'right' ? 'card-img-right' : 'card-img-left',
        ]) style="background-image: url({{ $getImageUrl() }})">
            @if ($imageCopyright)
                <small
                    class="tw-absolute tw-bottom-0 tw-left-0 tw-bg-black tw-text-white tw-px-1 tw-bg-opacity-70"
                >
                    &copy; {{ $replaceParameters($imageCopyright) }}
                </small>
            @endif
        </div>
    @endif

    <div class="card-body">
        @if ($title)
            <h2 class="card-title">{{ $replaceParameters($title) }}</h2>
        @endif

        @if ($text)
            {!! $replaceParameters($text) !!}
        @endif

        @if ($callToActions)
            <br>
            @foreach ($callToActions as $callToAction)
                <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
            @endforeach
        @endif
    </div>

</section>
