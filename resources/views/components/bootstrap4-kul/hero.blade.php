<header class="jumbotron2 kuleuven-banner">
    <div class="card card-banner card-50-50-right">
        <div class="card-img-right" style="background-image: url({{ $getHeroImageUrl() }})"></div>
        <div class="card-body">
            @if ($title)
                <h1>
                    {{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title) }}
                </h1>
            @endif

            @if ($intro)
                <p>
                    {!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($intro) !!}
                </p>
            @endif
        </div>
    </div>
</header>
