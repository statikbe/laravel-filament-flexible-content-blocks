<div class="py-20 section section--default">
    <div class="container px-4 mx-auto">
        {{-- TODO: Make this cleaner for left, center, right? --}}
        <div @class(['w-full', $getImageWidthClass(),
                      '' => $imagePosition === 'left',
                      'mx-auto' => $imagePosition === 'center',
                      'ml-auto' => $imagePosition === 'right'])>
            {{$getImageMedia(['alt' => $imageTitle, 'class'=> 'w-full', 'loading' => 'lazy'])}}
            @if($imageCopyright)
                <small>&copy; {{$imageCopyright}}</small>
            @endif
        </div>
    </div>
</div>
