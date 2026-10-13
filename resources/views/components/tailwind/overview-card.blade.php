<article class="bg-white flex flex-col justify-start items-start h-full relative transition duration-300 ease-out group hover:shadow-md">
    @if ($image)
        <div aria-hidden="true"
             class="px-2 pt-2 w-full [&>img]:w-full [&>img]:object-cover [&>img]:object-center"
        >
            {!! $image !!}
        </div>
    @endif

    <div class="flex flex-col justify-start items-start gap-y-4 p-4 md:p-6">
        @if ($title)
            <a href="{{ $url }}" class="before:absolute before:inset-0">
                <h3 class="">
                    {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title) }}
                </h3>
            </a>
        @endif

        @if ($description)
            <div class="canBeRichEditorContent">{!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($description) !!}</div>
        @endif

        @if ($url)
            <div class="flex justify-end">
                <span class="transition-transform duration-300 ease-out group-hover:translate-x-0.5"
                      aria-hidden="true">&rarr;</span>
            </div>
        @endif
    </div>
</article>
