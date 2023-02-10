<div class="">
    <div class="container px-4 mx-auto">
        @if($getHeroImageMedia)
            <div class="relative">
                {{$getHeroImageMedia(['alt' => $heroImageTitle, 'class' => 'w-full'])}}
                @if($heroImageCopyright)
                    <span class="absolute bottom-0 left-0 px-2 py-1 text-sm text-white bg-black/30">&copy; {{$heroImageCopyright}}</span>
                @endif
            </div>
        @endif
        <div class="p-6 bg-gray-200">
            @if($title)
                <h1 class="mb-4 text-3xl font-bold tracking-tight md:text-4xl">
                    {{ $title }}
                </h1>
            @endif

            @if($intro)
                <div class="font-light">
                    {!! $intro !!}
                </div>
            @endif
        </div>
    </div>
</div>
