<div class="section section--default">
    <div class="container">
        @if($hasOverlayImage())
            <div x-data="{isPlaying: false, embedUrl: '{{ $getEmbedSrc() }}'}" class="cursor-pointer group">
                <div class="relative flex items-center justify-center" x-show="!isPlaying" x-transition x-transition.delay.300ms>
                    {{ $getOverlayImageMedia(attributes:['alt' => '', 'class' => 'w-full', 'loading' => 'lazy']) }}
                    <div class="absolute inset-0">
                        <div class="flex items-center justify-center h-full">
                            <button class="flex flex-col items-center justify-center before:transition-all before:duration-300 before:ease-in-out group-hover:before:bg-black/30 before:absolute before:bg-black/0 before:inset-0"
                                @click="isPlaying = !isPlaying; $nextTick(() => { $refs.iframeElement.setAttribute('src', embedUrl) });">
                                <div class="relative z-10 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-16 h-16" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm14.024-.983a1.125 1.125 0 010 1.966l-5.603 3.113A1.125 1.125 0 019 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="text-white mt-4 text-lg text-center">@lang('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.video.sr_msg')</div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="bg-black" x-show="isPlaying" x-transition x-transition.delay.300ms x-cloak>
                    <iframe title="youtube embed" x-ref="iframeElement" src="" class="w-full aspect-video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            </div>
        @else
            <div class="w-full">
                {!! $getEmbedCode(['class' => 'w-full h-full aspect-video', 'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture', 'allowfullscreen' => true ]) !!}
            </div>
        @endif
    </div>
</div>
