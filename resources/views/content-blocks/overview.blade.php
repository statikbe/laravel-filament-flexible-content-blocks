<div class="py-12">
    <div class="container px-4 mx-auto">
        @if($title)
            <h2>{{$title}}</h2>
        @endif
        <div class="grid grid-cols-3 gap-4">
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
