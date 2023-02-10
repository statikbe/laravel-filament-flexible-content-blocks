@php
    $imagePositionClass = $imagePosition === 'right' ? 'md:flex-row-reverse' : '';
    // if ($getImageWidthClass() === 'w-3/4') {
    //     $imageWidthClass = 'md:w-3/4';
    //  } else if ($getImageWidthClass() === 'w-1/2') {
    //     $imageWidthClass = 'md:w-1/2';
    //  } else if ($getImageWidthClass() === 'w-1/3') {
    //     $imageWidthClass = 'md:w-1/3';
    //  } else { // w-full
    //     $imageWidthClass = '';
    // }
@endphp

<div class="py-20 section section--default">
    <div class="container px-4 mx-auto">
        <div @class(['w-full', $getImageWidthClass()])>
            {{$getImageMedia(['alt' => $imageTitle, 'class'=> 'w-full', 'loading' => 'lazy'])}}
            @if($imageCopyright)
                <small>&copy; {{$imageCopyright}}</small>
            @endif
        </div>
    </div>
</div>
