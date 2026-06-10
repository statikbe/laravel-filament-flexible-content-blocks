@php
    /**
     * @deprecated This block has very similar or fewer functionality than other blocks.
     * This block has been removed from the default blocks in the configuration.
     * Use TextImageBlock or CallToActionBlock.
     */
@endphp

@if ($content)
    <section class="row">
        <div class="col-xs-12">
            <div @class(['card', $getBackgroundColourClass() ])>
                <div class="card-body">
                    @if ($title)
                        <h2 class="card-title">{{ $replaceParameters($title) }}</h2>
                    @endif

                    @if ($content)
                        {!! $replaceParameters($content) !!}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
