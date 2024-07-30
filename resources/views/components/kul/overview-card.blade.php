<div {{ $attributes->merge(['class' => '']) }}>
    <div @class(['card', $colourClass])>
        <div class="card-img-top" style="background-image: url({{ $image }})"></div>
        <div class="card-body">
            @if ($title)
                <a href="{{ $url }}" class="before:absolute before:inset-0">
                    <h3>{{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title) }}
                    </h3>
                </a>
            @endif

            @if ($description)
                <p>{!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($description) !!}</p>
            @endif

            @if ($url)
                <br>
                <a href="{{ $url }}" class="btn btn-ghost-inv" aria-hidden="true">@lang('messages.overview_card_button_label')</a>
            @endif
        </div>
    </div>
</div>
