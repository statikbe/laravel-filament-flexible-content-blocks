<div class="py-12">
    <div class="container px-4 mx-auto">
        @if($title)
            <div class="mb-6 prose max-w-none">
                <h2>{{$replaceParameters($title)}}</h2>
            </div>
        @endif
        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-{{$gridColumns ?? 3}}">
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
