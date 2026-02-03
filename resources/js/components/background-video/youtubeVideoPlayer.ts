import { BaseVideoPlayer, VideoPlayerInitConfig } from './types';

export function getYoutubeVideoPlayer(): BaseVideoPlayer {
    let player: unknown;

    return {
        async init({
            playerElementId,
            videoId,
            videoWidth,
            videoHeight,
            shouldAutoplayVideo,
            prefersReducedMotion,
            onReady,
        }: VideoPlayerInitConfig): Promise<void> {
            await loadYouTubeApi();

            player = new YT.Player(playerElementId, {
                videoId,
                width: videoWidth,
                height: videoHeight,
                playerVars: {
                    autoplay: shouldAutoplayVideo ? 1 : 0,
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
                        if (prefersReducedMotion || !shouldAutoplayVideo) {
                            player.pauseVideo();
                            onReady({ isPLaying: false });
                        } else {
                            player.playVideo();
                            onReady({ isPLaying: true });
                        }

                        player.mute();
                    },
                    onError: (e) => {
                        console.warn('YouTube API error', e);
                    },
                },
            });
        },
        play() {
            player.playVideo();
        },
        pause() {
            player.pauseVideo();
        },
    };

    function loadYouTubeApi(): Promise<void> {
        return new Promise<void>((resolve) => {
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
    }
}
