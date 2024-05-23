<div class="relative py-10 bg-black sm:py-20 before:bg-black/25 before:z-10 before:absolute before:inset-0">
    <div class="container h-full px-4 mx-auto">
        <div class="relative z-10">
            @if($title)
                <h1 @if($hasHeroImage())class="text-white"@endif>
                    {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title) }}
                </h1>
            @endif

            @if($intro)
                <div class="w-full md:w-2/3 @if($hasHeroImage()) text-white @endif [&_a]:underline hover:[&_a]:no-underline">
                    {!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($intro) !!}
                </div>
            @endif
        </div>
    </div>
    @if($hasHeroImage())
        <div class="absolute inset-0">
            {{$getHeroImageMedia(null, [
                'class' => 'w-full h-full object-cover object-center',
                'loading' => 'lazy',
            ])}}
            @if($heroImageCopyright)
                <span class="absolute bottom-0 right-0 px-2 py-1 text-sm text-white bg-black/30 z-10">&copy; {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($heroImageCopyright) }}</span>
            @endif
        </div>
    @endif
</div>
