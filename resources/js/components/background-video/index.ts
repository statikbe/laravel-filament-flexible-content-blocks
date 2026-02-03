import { BaseVideoPlayer, VideoPlayerConfig, VideoPlayerData } from './types';
import { getYoutubeVideoPlayer } from './youtubeVideoPlayer';
import { debounce } from '../debounce';

const VIDEO_PLATFORM_YOUTUBE = 'youtube';

if (window.Alpine) {
    /* Alpine already initialized */

    initBackgroundVideoComponent();
} else {
    document.addEventListener('alpine:init', () => {
        /**
         * Executes after Alpine is loaded, but BEFORE it initializes itself on the page
         * See https://alpinejs.dev/essentials/lifecycle
         */

        initBackgroundVideoComponent();
    });
}

function initBackgroundVideoComponent() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    Alpine.data('initVideoPlayerData', initVideoPlayerData);

    function initVideoPlayerData({
        videoPlatform,
        videoId,
        playerElementId,
        videoWidth,
        videoHeight,
        minScreenWidthForAutoplay,
    }: VideoPlayerConfig): VideoPlayerData {
        let wrapperElement: Element = null;
        let videoPlayer: BaseVideoPlayer = determineVideoPlayer({ videoPlatform });

        return {
            isPlaying: false,
            isInitialized: false,
            initPlayer,
            playVideo,
            pauseVideo,
        };

        function initPlayer(wrapperElementInput: Element) {
            wrapperElement = wrapperElementInput;

            /* hiding the video itself from screen readers */
            wrapperElement.querySelector('#' + playerElementId).setAttribute('aria-hidden', 'true');

            const shouldAutoplayVideo = window.innerWidth >= minScreenWidthForAutoplay;

            videoPlayer.init({
                playerElementId,
                videoId,
                videoWidth,
                videoHeight,
                shouldAutoplayVideo,
                prefersReducedMotion,
                onReady: ({ isPLaying }) => {
                    this.isPlaying = isPLaying;
                },
            });

            initVideoRatio();

            this.isInitialized = true;
        }

        function initVideoRatio() {
            resizeVideo();

            window.addEventListener('resize', debounce(() => {
                resizeVideo({ isResize: true });
            }));
        }

        /**
         * To ensure that the video fills its container.
         */
        function resizeVideo({
            isResize = false,
        }: {
            isResize?: boolean;
        } = {}) {
            const containerWidth = wrapperElement.clientWidth;
            const containerHeight = wrapperElement.clientHeight;

            const videoElement = document.getElementById(playerElementId);

            const videoRatio = videoWidth / videoHeight;

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
        }

        function playVideo() {
            videoPlayer.play();

            this.isPlaying = true;
        }

        function pauseVideo() {
            videoPlayer.pause();

            this.isPlaying = false;
        }
    }
}

function determineVideoPlayer({ videoPlatform }: { videoPlatform: string }) {
    if (videoPlatform === VIDEO_PLATFORM_YOUTUBE) {
        return getYoutubeVideoPlayer();
    }

    throw new Error(`Unsupported video platform: "${videoPlatform}"`);
}
