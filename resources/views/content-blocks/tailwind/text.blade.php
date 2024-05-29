@if($content)
    <div class="section {{ $getBackgroundColourClass() }}">
        <div class="container">
            <div class="w-full md:w-3/4">
                @if($title)
                    <h2>{{$replaceParameters($title)}}</h2>
                @endif
                {!! $replaceParameters($content) !!}
            </div>
        </div>
    </div>
@endif
