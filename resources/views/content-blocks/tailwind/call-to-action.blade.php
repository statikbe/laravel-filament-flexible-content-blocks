<div class="section {{ $getBackgroundColourClass() }}">
    <div class="tw-container">
        <div @class(['tw-rounded-xl tw-overflow-hidden', 'tw-bg-white' => $getBackgroundColourClass == 'section--light', 'tw-bg-light' => $getBackgroundColourClass != 'section--light'])>
            <div @class(['tw-grid sm:tw-grid-cols-2 tw-gap-4' => $hasImage()])>
                @if($hasImage())
                    <div class="tw-relative tw-min-h-40">
                        <div class="tw-absolute tw-inset-0">
                            {{ $getImageMedia(['class'=> 'tw-w-full tw-h-full tw-object-cover tw-object-center', 'loading' => 'lazy'] )}}
                        </div>

                        @if($imageCopyright)
                            <small>&copy; {{$imageCopyright}}</small>
                        @endif
                    </div>
                @endif
                <div @class(['tw-w-full tw-p-6', 'md:tw-w-3/4 tw-text-center tw-mx-auto' => !$hasImage()])>
                    @if($title)
                        <h2>{{$replaceParameters($title)}}</h2>
                    @endif
                    @if($text)
                        <div>
                            {!! $replaceParameters($text) !!}
                        </div>
                    @endif
                    @if($callToActions)
                        <div @class(['tw-flex tw-flex-wrap tw-items-center tw-gap-4 tw-mt-6', 'tw-justify-center' => !$hasImage()])>
                            @foreach($callToActions as $callToAction)
                                <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
