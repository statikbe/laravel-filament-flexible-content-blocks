<div class="" x-data="{imageUrl: $refs.heroImage.currentSrc || $refs.heroImage.src}">
    <div class="container px-4 mx-auto">
        <div class="w-full h-48 bg-center bg-cover sm:h-96" :style="`background-image: url(${imageUrl})`"></div>
        @if($getHeroImageMedia)
            <div class="relative">
                {{$getHeroImageMedia(['class' => 'w-full sr-only', 'loading' => 'lazy', 'x-ref' => 'heroImage', 'x-on:load' => 'imageUrl = $refs.heroImage.currentSrc || $refs.heroImage.src'])}}
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
