@props([
    'videoPlatform',
    'videoId',
    'wrapperClass' => '',
    'buttonWrapperClass' => 'absolute top-4 md:top-8 right-4 md:right-8 z-30',
    'buttonClass' => 'w-8 md:w-12 text-white',
    'playButtonClass' => '',
    'playButtonIcon' => 'heroicon-o-play-circle',
    'playButtonAriaText' => 'Play Video',
    'pauseButtonClass' => '',
    'pauseButtonIcon' => 'heroicon-o-pause-circle',
    'pauseButtonAriaText' => 'Pause Video',
])

@php
    use Illuminate\Support\Str;

    $playerElementId = 'video-player_' . $videoPlatform->value . '_' . Str::uuid();
@endphp

<script>
    document.addEventListener('alpine:init', () => {
        /**
         * Executes after Alpine is loaded, but BEFORE it initializes itself on the page
         * See https://alpinejs.dev/essentials/lifecycle
         */

        Alpine.data('initVideoPlayerData', ({ videoPlatform, videoId, playerElementId }) => ({
            videoPlayer: null,
            isPlaying: null,

            initPlayer(element) {
                /* hiding the video itself from screen readers */
                element.querySelector('#' + playerElementId).setAttribute('aria-hidden', 'true');

                if (videoPlatform === 'youtube') {
                    this.loadYouTubeApi()
                        .then(() => {
                            this.videoPlayer = new YT.Player(playerElementId, {
                                videoId: videoId,
                                playerVars: {
                                    autoplay: 1,
                                    rel: 0, /* You can't disable "related videos" anymore, but when set to 0, related videos will come from the same channel as the video that was just played */
                                    playsinline: 1, /* Results in inline playback for mobile browsers and for WebViews created with the allowsInlineMediaPlayback property set to YES */
                                    controls: 0, /* Player controls do not display in the player */
                                    color: 'white', /* This parameter specifies the color that will be used in the player's video progress bar to highlight the amount of the video that the viewer has already seen. Valid parameter values are red and white */
                                    loop: 1, /* In the case of a single video player, a setting of 1 causes the player to play the initial video again and again */
                                    mute: 1,
                                },
                                events: {
                                    onReady: () => {
                                        this.videoPlayer.playVideo();
                                        this.isPlaying = true;

                                        this.videoPlayer.mute();
                                    },
                                    onError: (e) => {
                                        console.warn('YouTube API error', e);
                                    },
                                },
                            });
                        });
                }
            },

            loadYouTubeApi() {
                return new Promise((resolve) => {
                    if (window.YT && window.YT.Player) {
                        resolve();
                        return;
                    }

                    /**
                     * Defining a global function that will execute as soon as the YT player API code downloads.
                     * By calling 'resolve' it will cause the promise (returned by the loadYouTubeApi function)
                     * to be resolved, indicating that the player is ready.
                     */
                    window.onYouTubeIframeAPIReady = () => resolve();

                    const tag = document.createElement('script');
                    tag.src = "https://www.youtube.com/iframe_api";
                    document.head.appendChild(tag);
                });
            },

            playVideo() {
                if (videoPlatform === 'youtube') {
                    this.videoPlayer.playVideo();
                }

                this.isPlaying = true;
            },

            pauseVideo() {
                if (videoPlatform === 'youtube') {
                    this.videoPlayer.pauseVideo();
                }

                this.isPlaying = false;
            },
        }));
    });
</script>

<div @class([
         'relative overflow-hidden',
         $wrapperClass,
    ])
     x-data="initVideoPlayerData({
            videoPlatform: '{{ $videoPlatform->value }}',
            videoId: '{{ $videoId }}',
            playerElementId: '{{ $playerElementId }}',
         })"
     x-init="initPlayer($el)"
>
    <div id="{{ $playerElementId }}"
         class="!absolute top-0 left-0 w-full h-full z-20 pointer-events-none"
    ></div>

    <div x-show="videoPlayer"
         @class([
             $buttonWrapperClass,
         ])
    >
        <button type="button"
                class="group"
        >
            <span class="sr-only"
                  x-show="isPlaying"
            >{{ $playButtonAriaText }}</span>

            {{ svg($pauseButtonIcon, [
                'x-show' => 'isPlaying',
                'class' => $buttonClass . ' ' . $pauseButtonClass,
                'x-on:click' => 'pauseVideo();',
            ]) }}

            <span class="sr-only"
                  x-show="!isPlaying"
            >{{ $pauseButtonAriaText }}</span>

            {{ svg($playButtonIcon, [
                'x-show' => '!isPlaying',
                'class' => $buttonClass . ' ' . $playButtonClass,
                'x-on:click' => 'playVideo();',
            ]) }}
        </button>
    </div>
</div>
