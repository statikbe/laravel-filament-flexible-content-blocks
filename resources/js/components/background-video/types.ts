export interface VideoPlayerConfig {
    videoPlatform: string;
    videoId: string;
    playerElementId: string;
    videoWidth: number;
    videoHeight: number;
    minScreenWidthForAutoplay: number;
}

export interface VideoPlayerData {
    isInitialized: boolean;
    isPlaying: boolean;
    initPlayer: (wrapperElement: Element) => void;
    playVideo: () => void;
    pauseVideo: () => void;
}

export interface BaseVideoPlayer {
    init(config: VideoPlayerInitConfig): Promise<void>
    play(): void;
    pause(): void;
}

export interface VideoPlayerInitConfig extends Pick<VideoPlayerConfig, 'playerElementId' | 'videoId' | 'videoWidth' | 'videoHeight'> {
    shouldAutoplayVideo: boolean;
    prefersReducedMotion: boolean;
    onReady: (props: VideoPlayerInitOnReadyResult) => void;
}

export interface VideoPlayerInitOnReadyResult {
    isPLaying: boolean;
}
