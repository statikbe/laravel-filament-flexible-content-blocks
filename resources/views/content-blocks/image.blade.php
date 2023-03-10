<div class="py-12 {{ $getBackgroundColourClass() }}">
    <div class="container px-4 mx-auto">
        {{-- TODO: Make this cleaner for left, center, right? --}}
        <div @class(['w-full', $getImageWidthClass(),
                      '' => $imagePosition === 'left',
                      'mx-auto' => $imagePosition === 'center',
                      'ml-auto' => $imagePosition === 'right'])>
            {{$getImageMedia(attributes: ['alt' => $imageTitle, 'class'=> 'w-full', 'loading' => 'lazy'])}}
            @if($imageCopyright)
                <small>&copy; {{$imageCopyright}}</small>
            @endif
        </div>
    </div>
</div>
