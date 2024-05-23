<div class="section section--default">
    <div class="container">
        @if($title)
            <h2>{{$replaceParameters($title)}}</h2>
        @endif
        <div @class(['grid gap-4 ', 'sm:grid-cols-2 md:grid-cols-' . $gridColumns => $gridColumns > 1])>
            @foreach($getOverviewItems() as $overviewItem)
                @php
                    /* @var \Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes&Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable $overviewItem */
                @endphp

                <x-flexible-overview-card :title="$overviewItem->getOverviewTitle()"
                                        :description="$overviewItem->getOverviewDescription()"
                                        :image="$overviewItem->getOverviewImageMedia()"
                                        :url="$overviewItem->getViewUrl()"></x-flexible-overview-card>
            @endforeach
        </div>
    </div>
</div>
