@php
    $imagePositionClass = $getImageMedia && $imagePosition === 'right' ? 'md:flex-row-reverse' : '';
    $imageSizes = '(max-width: 479px) 95vw, (min-width: 480px) and (max-width: 659px) 448px, (min-width: 660px) and (max-width: 819px) 334px, (min-width: 820px) and (max-width: 979px) 414px, (min-width: 980px) and (max-width: 1279px) 494px, (min-width: 1280px) 736px';
@endphp

<div class="py-20 section section--default">
    <div class="container px-4 mx-auto">
        <div class="flex flex-wrap -mx-4 {{ $imagePositionClass }}">
            @if($getImageMedia)
                <div class="w-full px-4 mb-4 md:w-1/2 md:mb-0">
                    {{$getImageMedia(['alt' => $imageTitle, 'sizes' => $imageSizes])}}

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