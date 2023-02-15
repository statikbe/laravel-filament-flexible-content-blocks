<div class="py-20 section section--default">
    <div class="container px-4 mx-auto">
        <div @class(['flex flex-wrap -mx-4'])>
            @if($hasImage())
                <div class="w-full px-4 mb-4 md:w-1/2 md:mb-0">
                    {{$getImageMedia(['class'=> 'w-full', 'loading' => 'lazy'])}}

                    @if($imageCopyright)
                        <small>&copy; {{$imageCopyright}}</small>
                    @endif
                </div>
            @endif
            <div class="w-full px-4 @if($hasImage())md:w-1/2 @else md:w-3/4 @endif">
                @if($title)
                    <h2 class="mb-4 text-3xl">{{$title}}</h2>
                @endif
                @if($text)
                    <div>
                        {!! $text !!}
                    </div>
                @endif
                @if($callToAction)
                    <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                @endif
            </div>
        </div>
    </div>
</div>
