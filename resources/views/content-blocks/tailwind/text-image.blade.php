<div class="section {{ $getBackgroundColourClass() }}">
    <div class="container">
        <div @class(['flex flex-wrap -mx-4', 'md:flex-row-reverse' => $hasImage() && $imagePosition === 'right'])>
            @if($hasImage())
                <div class="w-full px-4 mb-4 md:w-1/2 md:mb-0">
                    {{$getImageMedia(attributes: ['class'=> 'w-full', 'loading' => 'lazy'])}}

                    @if($imageCopyright)
                        <small>&copy; {{$replaceParameters($imageCopyright)}}</small>
                    @endif
                </div>
            @endif
            <div class="w-full px-4 @if($hasImage())md:w-1/2 @else md:w-3/4 @endif">
                @if($title)
                    <h2>{{$replaceParameters($title)}}</h2>
                @endif
                @if($text)
                    <div>
                        {!! $replaceParameters($text) !!}
                    </div>
                @endif
                @if($callToActions)
                    <div class="flex flex-wrap items-center gap-4 mt-6">
                        @foreach($callToActions as $callToAction)
                            <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
