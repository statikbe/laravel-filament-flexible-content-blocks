<x-filament::section>
    <div @class(['grid sm:grid-cols-2 gap-6' => $hasImage()])>
        @if ($hasImage())
            <div class="relative min-h-40 overflow-hidden rounded-xl">
                {{ $getImageMedia(['class' => 'w-full h-full object-cover object-center rounded-xl', 'loading' => 'lazy']) }}
                @if ($imageCopyright)
                    <p class="absolute bottom-2 right-2 text-xs text-white/70">&copy; {{ $imageCopyright }}</p>
                @endif
            </div>
        @endif
        <div @class(['flex flex-col justify-center gap-4', 'text-center items-center' => !$hasImage()])>
            @if ($title)
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $replaceParameters($title) }}</h2>
            @endif
            @if ($text)
                <div class="prose max-w-none dark:prose-invert">
                    {!! $replaceParameters($text) !!}
                </div>
            @endif
            @if ($callToActions)
                <div @class([
                    'flex flex-wrap items-center gap-3',
                    'justify-center' => !$hasImage(),
                ])>
                    @foreach ($callToActions as $callToAction)
                        <x-flexible-call-to-action :data="$callToAction" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament::section>
