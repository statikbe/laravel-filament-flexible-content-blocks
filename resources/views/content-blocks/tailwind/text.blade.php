@if($content)
    <div class="py-12 section section--default {{ $getBackgroundColourClass() }}">
        <div class="container px-4 mx-auto">
            <div class="w-full md:w-3/4">
                <div class="prose max-w-none">
                    @if($title)
                        <h2>{{$replaceParameters($title)}}</h2>
                    @endif
                    {!! $replaceParameters($content) !!}
                </div>
            </div>
        </div>
    </div>
@endif
