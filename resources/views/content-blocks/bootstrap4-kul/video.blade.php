<section class="section section--default" aria-label="video">
    <div class="tw-container">
        @if ($hasOverlayImage())
            <div x-data="{ isPlaying: false, embedUrl: '{{ $getEmbedSrc() }}' }" class="tw-cursor-pointer tw-group" aria-hidden="true">
                <div class="tw-relative tw-flex tw-items-center tw-justify-center" x-show="!isPlaying" x-transition
                     x-transition.delay.300ms>
                    {{ $getOverlayImageMedia(attributes: ['alt' => '', 'class' => 'tw-w-full', 'loading' => 'lazy']) }}
                    <div class="tw-absolute tw-inset-0">
                        <div class="tw-flex tw-items-center tw-justify-center tw-h-full">
                            <button class="tw-bg-transparent tw-flex tw-flex-col tw-items-center tw-justify-center before:tw-transition-all before:tw-duration-300 before:tw-ease-in-out group-hover:before:tw-bg-black/30 before:tw-absolute before:tw-bg-black/0 before:tw-inset-0 group-hover:tw-bg-black/0"
                                    @click="isPlaying = !isPlaying; $nextTick(() => { $refs.iframeElement.setAttribute('src', embedUrl) });">
                                <div
                                     class="tw-relative tw-z-10 tw-text-white tw-p-2 tw-bg-black tw-rounded-full tw-bg-opacity-70 ">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="tw-w-16 tw-h-16"
                                         fill="tw-currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm14.024-.983a1.125 1.125 0 010 1.966l-5.603 3.113A1.125 1.125 0 019 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div
                                     class="tw-text-white tw-mt-4 tw-text-lg tw-text-center tw-px-2 tw-bg-black tw-rounded-lg tw-bg-opacity-70">
                                    @lang('filament-flexible-content-blocks::filament-flexible-content-blocks.form_component.content_blocks.video.sr_msg')</div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="tw-bg-black" x-show="isPlaying" x-transition x-transition.delay.300ms x-cloak>
                    <iframe title="youtube embed" x-ref="iframeElement" src="" class="tw-w-full tw-aspect-video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            </div>
        @else
            <div class="tw-w-full">
                {!! $getEmbedCode([
                    'class' => 'tw-w-full tw-h-full tw-aspect-video',
                    'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture',
                    'allowfullscreen' => true,
                ]) !!}
            </div>
        @endif
    </div>
</section>
