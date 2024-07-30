<section @class(['section section--light' => !$hasHeroImage(), 'tw-relative tw-py-10 tw-bg-light sm:tw-py-20 before:tw-bg-black/25 before:tw-z-10 before:tw-absolute before:tw-inset-0' => $hasHeroImage()])>
    <div class="tw-container">
        <div class="tw-relative tw-z-10">
            @if($title)
                <h1 @if($hasHeroImage())class="tw-text-white"@endif>
                    {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title) }}
                </h1>
            @endif

            @if($intro)
                <div class="tw-w-full md:tw-w-2/3 tw-text-lg md:tw-text-xl @if($hasHeroImage()) tw-text-white @endif [&_a]:tw-underline hover:[&_a]:tw-no-underline">
                    {!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($intro) !!}
                </div>
            @endif
        </div>
    </div>
    @if($hasHeroImage())
        <div class="tw-absolute tw-inset-0">
            {{$getHeroImageMedia(null, [
                'class' => 'tw-w-full tw-h-full tw-object-cover tw-object-center',
                'loading' => 'lazy',
            ])}}
            @if($heroImageCopyright)
                <small class="tw-absolute tw-bottom-0 tw-right-0 tw-px-2 tw-py-1 tw-text-white tw-bg-black/30 tw-z-10">&copy; {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($heroImageCopyright) }}</small>
            @endif
        </div>
    @endif
</section>
