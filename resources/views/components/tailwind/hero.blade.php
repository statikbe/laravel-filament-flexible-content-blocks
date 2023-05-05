<div class="relative overflow-hidden w-full min-h-[192px] bg-center bg-cover py-10 sm:h-[380px] before:bg-black/40 before:z-10 before:absolute before:inset-0 hero">
    <div class="container h-full px-4 mx-auto">
        <div class="relative z-10 flex flex-col justify-center h-full grow">
            @if($title)
                <h1 class="text-3xl font-bold tracking-tight hero__title @if($hasHeroImage())text-white drop-shadow @endif md:text-4xl @if($intro) mb-4 @endif">
                    {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title) }}
                </h1>
            @endif

            @if($intro)
                <div class="max-w-4xl font-light text-white drop-shadow hero__intro [&_a]:underline hover:[&_a]:no-underline">
                    {!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($intro) !!}
                </div>
            @endif
        </div>
    </div>
    @if($hasHeroImage())
        <div class="absolute inset-0 w-full h-full">
            {{$getHeroImageMedia(null, [
                'class' => 'absolute inse-0 w-full h-full object-cover hero__image',
                'loading' => 'lazy',
            ])}}
            @if($heroImageCopyright)
                <span class="absolute bottom-0 right-0 px-2 py-1 text-sm text-white bg-black/30 hero__copyright">&copy; {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($heroImageCopyright) }}</span>
            @endif
        </div>
    @endif
</div>
