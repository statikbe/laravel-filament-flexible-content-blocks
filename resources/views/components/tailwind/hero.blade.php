<section @class([
    'section section--light' => !$hasHeroImage(),
    'relative py-10 bg-light sm:py-20 before:bg-black/25 before:z-10 before:absolute before:inset-0' => $hasHeroImage(),
])>
    <div class="container">
        <div class="relative z-10">
            @if ($title)
                <h1 @if ($hasHeroImage()) class="text-white" @endif>
                    {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title) }}
                </h1>
            @endif

            @if ($intro)
                <div
                     class="w-full md:w-2/3 text-lg md:text-xl @if ($hasHeroImage()) text-white @endif [&_a]:underline hover:[&_a]:no-underline">
                    {!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($intro) !!}
                </div>
            @endif

            @if ($heroCallToActions)
                <div class="flex flex-wrap gap-4 mt-4">
                    @foreach ($heroCallToActions as $callToAction)
                        <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if ($hasHeroVideoUrl())
{{--        <x-flexible-video-background :videoUrl="$getHeroVideoUrl()"--}}
        <x-flexible-video-background videoUrl="https://vimeo.com/347119375"
                                     wrapperClass="min-h-[337px] md:min-h-[474px]"
        />
    @elseif ($hasHeroImage())
        <div class="absolute inset-0">
            {{ $getHeroImageMedia(null, [
                'class' => 'w-full h-full object-cover object-center',
                'loading' => 'lazy',
            ]) }}
            @if ($heroImageCopyright)
                <small class="absolute bottom-0 right-0 px-2 py-1 text-white bg-black/30 z-10">&copy;
                    {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($heroImageCopyright) }}</small>
            @endif
        </div>
    @endif
</section>
