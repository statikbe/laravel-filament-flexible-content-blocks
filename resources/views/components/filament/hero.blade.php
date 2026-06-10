<section @class([
    'relative overflow-hidden rounded-xl',
    'bg-gray-50 dark:bg-gray-900' => !$hasHeroImage(),
    'py-10 sm:py-20 before:absolute before:inset-0 before:z-10 before:bg-black/25' => $hasHeroImage(),
])>
    <div class="relative z-20 p-6 sm:p-10">
        @if ($title)
            <h1 @class([
                'text-3xl font-bold',
                'text-white' => $hasHeroImage(),
                'text-gray-900 dark:text-white' => !$hasHeroImage(),
            ])>
                {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title) }}
            </h1>
        @endif

        @if ($intro)
            <div @class([
                'mt-4 w-full text-lg md:w-2/3 md:text-xl [&_a]:underline hover:[&_a]:no-underline',
                'text-white' => $hasHeroImage(),
                'text-gray-600 dark:text-gray-300' => !$hasHeroImage(),
            ])>
                {!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($intro) !!}
            </div>
        @endif

        @if ($heroCallToActions)
            <div class="mt-4 flex flex-wrap gap-3">
                @foreach ($heroCallToActions as $callToAction)
                    <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                @endforeach
            </div>
        @endif
    </div>

    @if ($hasHeroVideoUrl())
        <x-flexible-background-video :videoUrl="$getHeroVideoUrl()"
                                     wrapperClass="min-h-[337px] md:min-h-[474px]"
                                     :overlayImageMedia="$hasHeroImage()
                                         ? $getHeroImageMedia(null, [
                                             'class' => 'w-full h-full object-cover object-center',
                                             'loading' => 'lazy',
                                         ])
                                         : null"
        />
    @elseif ($hasHeroImage())
        <div class="absolute inset-0">
            {{ $getHeroImageMedia(null, [
                'class' => 'w-full h-full object-cover object-center',
                'loading' => 'lazy',
            ]) }}
            @if ($heroImageCopyright)
                <small class="absolute bottom-0 right-0 z-20 bg-black/30 px-2 py-1 text-white">&copy;
                    {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($heroImageCopyright) }}</small>
            @endif
        </div>
    @endif
</section>
