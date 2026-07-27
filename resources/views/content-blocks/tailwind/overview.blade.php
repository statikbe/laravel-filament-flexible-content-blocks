@php
    $overviewItems = $getOverviewItems();
@endphp

@if ($overviewItems)
    <div @class([
        'content-block content-block--overview',
        $getBackgroundColourClass(),
    ])>
        <div class="container">
            <div class="flex flex-col gap-3 md:gap-6">
                @if ($title)
                    <h2 class="text-balance">{{$replaceParameters($title)}}</h2>
                @endif

                @php
                    $nrOfItems = count($overviewItems);
                    $actualNrGridCols = min($gridColumns, $nrOfItems);

                    $isCol2 = $actualNrGridCols >= 2;
                    $isCol3 = $actualNrGridCols >= 3;
                    $isCol4 = $actualNrGridCols === 4;
                @endphp

                <ul @class([
                    'grid gap-x-6 gap-y-4 sm:gap-y-6',
                    'sm:grid-cols-2' => $isCol2,
                    'lg:grid-cols-3' => $isCol3,
                    'xl:grid-cols-4' => $isCol4,
                ])>
                    @foreach($overviewItems as $overviewItem)
                        @php
                            /* @var \Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes&Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable $overviewItem */
                        @endphp

                        <li @class([
                            'border border-gray-300 rounded-xl overflow-clip',
                            'max-w-md' => count($overviewItems) === 1,
                        ])>
                            <x-flexible-overview-card :title="$overviewItem->getOverviewTitle()"
                                                      :description="$overviewItem->getOverviewDescription()"
                                                      :image="$overviewItem->getOverviewImageMedia(null, ['class' => 'w-full'])"
                                                      :url="$overviewItem->getViewUrl()"></x-flexible-overview-card>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
