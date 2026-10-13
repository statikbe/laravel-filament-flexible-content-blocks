<x-filament::section class="group relative h-full transition duration-300 ease-out hover:shadow-md">
    @if ($image)
        <div class="mb-4">{!! $image !!}</div>
    @endif

    @if ($title)
        <a href="{{ $url }}" class="before:absolute before:inset-0">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title) }}
            </h3>
        </a>
    @endif

    @if ($description)
        <div class="mt-2 text-gray-600 dark:text-gray-300">
            {!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($description) !!}
        </div>
    @endif

    @if ($url)
        <div class="mt-4 flex justify-end">
            <span class="transition-transform duration-300 ease-out group-hover:translate-x-0.5"
                  aria-hidden="true">&rarr;</span>
        </div>
    @endif
</x-filament::section>
