<div class="section {{ $getBackgroundColourClass() }}">
    <div class="container">
        <div @class(['rounded-xl overflow-hidden', 'bg-white' => $getBackgroundColourClass == 'section--light', 'bg-light' => $getBackgroundColourClass != 'section--light'])>
            <div @class(['grid sm:grid-cols-2 gap-4' => $hasImage()])>
                @if($hasImage())
                    <div class="relative min-h-40">
                        <div class="absolute inset-0">
                            {{ $getImageMedia(['class'=> 'w-full h-full object-cover object-center', 'loading' => 'lazy'] )}}
                        </div>

                        @if($imageCopyright)
                            <small>&copy; {{$imageCopyright}}</small>
                        @endif
                    </div>
                @endif
                <div @class(['w-full p-6', 'md:w-3/4 text-center mx-auto' => !$hasImage()])>
                    @if($title)
                        <h2>{{$replaceParameters($title)}}</h2>
                    @endif
                    @if($text)
                        <div>
                            {!! $replaceParameters($text) !!}
                        </div>
                    @endif
                    @if($callToActions)
                        <div @class(['flex flex-wrap items-center gap-4 mt-6', 'justify-center' => !$hasImage()])>
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
