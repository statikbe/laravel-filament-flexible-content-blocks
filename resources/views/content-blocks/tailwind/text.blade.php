@if($content)
    <section class="section {{ $getBackgroundColourClass() }}">
        <div class="tw-container">
            <div class="tw-w-full md:tw-w-3/4">
                @if($title)
                    <h2>{{$replaceParameters($title)}}</h2>
                @endif
                {!! $replaceParameters($content) !!}
            </div>
        </div>
    </section>
@endif
