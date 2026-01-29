@props([
    'videoPlatform',
    'videoId',
    'videoWidth' => 640,
    'videoHeight' => 360,
    'wrapperClass' => '',
    'buttonWrapperClass' => 'absolute top-4 md:top-8 right-4 md:right-8 z-40',
    'buttonClass' => 'rounded-full bg-white/70 flex flex-row justify-center items-center p-1 md:p-2',
    'buttonIconClass' => 'w-8 md:w-10 text-black group-hover:text-black/70',
    'playButtonClass' => '',
    'playButtonIcon' => 'heroicon-o-play-circle',
    'playButtonAriaText' => 'Play Video',
    'pauseButtonClass' => '',
    'pauseButtonIcon' => 'heroicon-o-pause-circle',
    'pauseButtonAriaText' => 'Pause Video',
    'minScreenWidthForAutoplay' => 768,
    'overlayImageMedia' => null, /* when provided, this image will be shown whenever the video is paused (so also on mobile screens where autoplay is off) */
])

@php
    use Illuminate\Support\Str;

    $playerElementId = 'video-player_' . $videoPlatform->value . '_' . Str::uuid();
@endphp

@once
    @section('scripts')
        @parent
        @vite(['vendor/statikbe/laravel-filament-flexible-content-blocks/resources/js/components/background-video/index.ts'])
    @endsection
@endonce

<div @class([
         'relative overflow-hidden',
         'w-full h-full',
         $wrapperClass,
     ])
     x-data="initVideoPlayerData({
            videoPlatform: '{{ $videoPlatform->value }}',
            videoId: '{{ $videoId }}',
            videoWidth: '{{ $videoWidth }}',
            videoHeight: '{{ $videoHeight }}',
            playerElementId: '{{ $playerElementId }}',
            minScreenWidthForAutoplay: '{{ $minScreenWidthForAutoplay }}',
         })"
     x-init="initPlayer($el)"
>
    <div id="{{ $playerElementId }}"
         class="!absolute top-0 left-0 h-full z-20 pointer-events-none"
    ></div>

    @if ($overlayImageMedia)
        <div x-show="!isPlaying"
             class="absolute inset-0 z-30"
        >
            {!! $overlayImageMedia !!}
        </div>
    @endif

    <div x-show="isInitialized"
         @class([
             $buttonWrapperClass,
         ])
    >
        <button type="button"
                @class([
                    'group',
                    $buttonClass,
                ])
        >
            <span class="sr-only"
                  x-show="isPlaying"
            >{{ $playButtonAriaText }}</span>

            {{ svg($pauseButtonIcon, [
                'x-show' => 'isPlaying',
                'class' => $buttonIconClass . ' ' . $pauseButtonClass,
                'x-on:click' => 'pauseVideo();',
            ]) }}

            <span class="sr-only"
                  x-show="!isPlaying"
            >{{ $pauseButtonAriaText }}</span>

            {{ svg($playButtonIcon, [
                'x-show' => '!isPlaying',
                'class' => $buttonIconClass . ' ' . $playButtonClass,
                'x-on:click' => 'playVideo();',
            ]) }}
        </button>
    </div>
</div>
