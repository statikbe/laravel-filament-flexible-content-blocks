<x-filament::section aria-label="video">
    @if ($hasOverlayImage())
        <div x-data="{ isPlaying: false, embedUrl: '{{ $getEmbedSrc() }}' }" class="cursor-pointer group" aria-hidden="true">
            <div class="relative flex items-center justify-center overflow-hidden rounded-xl" x-show="!isPlaying" x-transition>
                {{ $getOverlayImageMedia(attributes: ['alt' => '', 'class' => 'w-full rounded-xl', 'loading' => 'lazy']) }}
                <div class="absolute inset-0 flex items-center justify-center">
                    <button
                        class="relative z-10 flex flex-col items-center gap-3 text-white"
                        @click="isPlaying = !isPlaying; $nextTick(() => { $refs.iframeElement.setAttribute('src', embedUrl) });"
                    >
                        <span class="rounded-full bg-black/70 p-4 transition group-hover:bg-black/90">
                            <x-heroicon-s-play class="h-12 w-12" aria-hidden="true" />
                        </span>
                        <span class="rounded-lg bg-black/70 px-3 py-1 text-center text-lg">
                            @lang('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.video.sr_msg')
                        </span>
                    </button>
                </div>
            </div>
            <div class="overflow-hidden rounded-xl bg-black" x-show="isPlaying" x-transition x-cloak>
                <iframe
                    title="video"
                    x-ref="iframeElement"
                    src=""
                    class="aspect-video w-full"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                ></iframe>
            </div>
        </div>
    @else
        <div class="overflow-hidden rounded-xl">
            {!! $getEmbedCode([
                'class' => 'aspect-video w-full',
                'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture',
                'allowfullscreen' => true,
            ]) !!}
        </div>
    @endif
</x-filament::section>
