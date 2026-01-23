@props([
    'videoPlatform',
    'videoId',
    'videoWidth' => 640,
    'videoHeight' => 360,
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

        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        Alpine.data('initVideoPlayerData', ({ videoPlatform, videoId, videoWidth, videoHeight, playerElementId }) => ({
            videoWidth,
            videoHeight,
            playerElementId,
            wrapperElement: null,
            videoPlayer: null,
            isPlaying: null,

            initPlayer(wrapperElement) {
                this.wrapperElement = wrapperElement;

                /* hiding the video itself from screen readers */
                wrapperElement.querySelector('#' + playerElementId).setAttribute('aria-hidden', 'true');

                if (videoPlatform === 'youtube') {
                    this.loadYouTubeApi()
                        .then(() => {
                            this.videoPlayer = new YT.Player(playerElementId, {
                                videoId: videoId,
                                height: videoHeight,
                                width: videoWidth,
                                playerVars: {
                                    autoplay: 1,
                                    rel: 0, /* You can't disable "related videos" anymore, but when set to 0, related videos will come from the same channel as the video that was just played */
                                    playsinline: 1, /* Results in inline playback for mobile browsers and for WebViews created with the allowsInlineMediaPlayback property set to YES */
                                    controls: 0, /* Player controls do not display in the player */
                                    color: 'white', /* This parameter specifies the color that will be used in the player's video progress bar to highlight the amount of the video that the viewer has already seen. Valid parameter values are red and white */
                                    loop: 1, /* In the case of a single video player, a setting of 1 causes the player to play the initial video again and again */
                                    mute: 1,
                                    playlist: videoId, /* has to be a playlist - even when only 1 video - otherwise the loop does not work */
                                },
                                events: {
                                    onReady: () => {
                                        if (prefersReducedMotion) {
                                            this.videoPlayer.pauseVideo();
                                            this.isPlaying = false;
                                        } else {
                                            this.videoPlayer.playVideo();
                                            this.isPlaying = true;
                                        }

                                        this.videoPlayer.mute();
                                    },
                                    onError: (e) => {
                                        console.warn('YouTube API error', e);
                                    },
                                },
                            });
                        });

                    this.initVideoRatio();
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

            initVideoRatio() {
                this.resizeVideo();

                window.addEventListener('resize', this.debounce(() => {
                    this.resizeVideo({ isResize: true });
                }));
            },

            /**
             * To ensure that the video fills its container.
             */
            resizeVideo({ isResize = false } = {}) {
                const containerWidth = this.wrapperElement.clientWidth;
                const containerHeight = this.wrapperElement.clientHeight;

                const videoElement = document.getElementById(this.playerElementId);

                const videoRatio = this.videoWidth / this.videoHeight;

                if (containerWidth / containerHeight >= videoRatio) {
                    /**
                     * The container ratio is larger than the (16:9) video ratio
                     * --> We stretch the video height to a height larger than the container (overflow)
                     *     so that the resulting width becomes the container width
                     */

                    const newVideoHeight = Math.ceil(containerWidth * (videoRatio));

                    videoElement.style.left = `-4px`;
                    videoElement.style.width = `101%`;
                    videoElement.style.height = `${newVideoHeight}px`;
                    videoElement.style.transform = `translateY(-${(newVideoHeight - containerHeight) / 2}px)`;
                } else {
                    /**
                     * The container ratio is smaller than the (16:9) video ratio
                     * --> We stretch the video width to a width larger than the container (overflow)
                     *     so that the resulting height becomes the container height
                     */

                    /* we use the amplifier to make sure the "black padding" - added by youtube - is not visible anymore */
                    const amplifier = containerWidth < 500
                        ? 1.1
                        : 1.4;

                    const newVideoWidth = Math.ceil(containerHeight * (videoRatio) * amplifier);
                    const newVideoHeight = Math.ceil(newVideoWidth * (1 / videoRatio));

                    videoElement.style.width = `${newVideoWidth}px`;
                    videoElement.style.height = `${newVideoHeight}px`;
                    videoElement.style.transform = `translateX(-${(newVideoWidth - containerWidth) / 2}px) translateY(-${(newVideoHeight - containerHeight) / 2}px)`;
                }
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

            debounce(func, timeout = 100) {
                let timer;
                return (...args) => {
                    clearTimeout(timer);
                    timer = setTimeout(() => { func.apply(this, args); }, timeout);
                };
            },
        }));
    });
</script>

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
         })"
     x-init="initPlayer($el)"
>
    <div id="{{ $playerElementId }}"
         class="!absolute top-0 left-0 h-full z-20 pointer-events-none"
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
