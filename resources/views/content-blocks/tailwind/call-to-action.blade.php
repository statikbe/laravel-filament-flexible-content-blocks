<div class="py-12 {{ $getBackgroundColourClass() }}">
    <div class="container px-4 mx-auto">
        <div @class(['flex flex-wrap -mx-4'])>
            @if($hasImage())
                <div class="w-full px-4 mb-4 md:w-1/2 md:mb-0">
                    {{$getImageMedia(['class'=> 'w-full', 'loading' => 'lazy'])}}

                    @if($imageCopyright)
                        <small>&copy; {{$imageCopyright}}</small>
                    @endif
                </div>
            @endif
            <div class="w-full px-4 @if($hasImage())md:w-1/2 @else md:w-3/4 @endif">
                <div class="prose max-w-none">
                    @if($title)
                        <h2>{{$replaceParameters($title)}}</h2>
                    @endif
                    @if($text)
                        <div>
                            {!! $replaceParameters($text) !!}
                        </div>
                    @endif
                    @if($callToActions)
                        <div class="flex flex-wrap items-center gap-4 not-prose">
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
