<section>
    @if ($title)
        <div class="card card-primary">
            <h2>{{ $replaceParameters($title) }}</h2>
        </div>
    @endif

    <div class="row">
        @foreach ($getOverviewItems() as $overviewItem)
            @php
                /* @var \Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes&Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable $overviewItem */
            @endphp

            <x-flexible-overview-card :title="$overviewItem->getOverviewTitle()" :description="$overviewItem->getOverviewDescription()" :image="$overviewItem->getOverviewImageUrl()" :url="$overviewItem->getViewUrl()"
                                      :colourClass="$getBackgroundColourClass()" @class([
                                          'col-xs-24 col-md-12' => $gridColumns == 1,
                                          'col-xs-12 col-md-6' => $gridColumns == 2,
                                          'col-xs-8 col-md-4' => $gridColumns == 3,
                                          'col-xs-6 col-md-3' => $gridColumns == 4,
                                      ]) />
        @endforeach
    </div>
</section>
