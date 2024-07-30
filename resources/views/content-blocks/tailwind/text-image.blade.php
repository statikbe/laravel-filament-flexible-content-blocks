<section class="section {{ $getBackgroundColourClass() }}">
    <div class="tw-container">
        <div @class(['tw-flex tw-flex-wrap tw--mx-4', 'md:tw-flex-row-reverse' => $hasImage() && $imagePosition === 'right'])>
            @if($hasImage())
                <div class="tw-w-full tw-px-4 tw-mb-4 md:tw-w-1/2 md:tw-mb-0">
                    {{$getImageMedia(attributes: ['class'=> 'tw-w-full', 'loading' => 'tw-lazy'])}}

                    @if($imageCopyright)
                        <small>&copy; {{$replaceParameters($imageCopyright)}}</small>
                    @endif
                </div>
            @endif
            <div class="tw-w-full tw-px-4 @if($hasImage())md:tw-w-1/2 @else md:tw-w-3/4 @endif">
                @if($title)
                    <h2>{{$replaceParameters($title)}}</h2>
                @endif
                @if($text)
                    <div>
                        {!! $replaceParameters($text) !!}
                    </div>
                @endif
                @if($callToActions)
                    <div class="tw-flex tw-flex-wrap tw-items-center tw-gap-4 mt-6">
                        @foreach($callToActions as $callToAction)
                            <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
