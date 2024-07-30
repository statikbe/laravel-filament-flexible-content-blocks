<section class="section {{ $getBackgroundColourClass() }}">
    <div class="tw-container">
        <div @class(['tw-w-full', $getImageWidthClass(),
                      '' => $imagePosition === 'left',
                      'tw-mx-auto' => $imagePosition === 'center',
                      'tw-ml-auto' => $imagePosition === 'right'])>
            {{$getImageMedia(attributes: ['alt' => $imageTitle, 'class'=> 'tw-w-full', 'loading' => 'lazy'])}}
            @if($imageCopyright)
                <small>&copy; {{$replaceParameters($imageCopyright)}}</small>
            @endif
        </div>
    </div>
</section>
