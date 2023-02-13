<div class="py-20 section section--default">
    <div class="container px-4 mx-auto">
        <div @class(['flex flex-wrap -mx-4', 'md:flex-row-reverse' => $getImageMedia && $imagePosition === 'right'])>
            @if($getImageMedia)
                <div class="w-full px-4 mb-4 md:w-1/2 md:mb-0">
                    {{$getImageMedia(['class'=> 'w-full', 'loading' => 'lazy'])}}

                    @if($imageCopyright)
                        <small>&copy; {{$imageCopyright}}</small>
                    @endif
                </div>
            @endif
            <div class="w-full px-4 @if($getImageMedia)md:w-1/2 @else md:w-3/4 @endif">
                @if($title)
                    <h2 class="mb-4 text-3xl">{{$title}}</h2>
                @endif
                @if($content)
                    <div>
                        {!! $content !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>