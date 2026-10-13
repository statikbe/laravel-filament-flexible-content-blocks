@php
    $overviewItems = $getOverviewItems();
    /* Build the grid column classes as complete literals so Tailwind can detect
       them — a concatenated `md:grid-cols-{$n}` string is never generated. */
    $gridColsClass = match ((int) $gridColumns) {
        2 => 'sm:grid-cols-2',
        3 => 'sm:grid-cols-2 lg:grid-cols-3',
        4 => 'sm:grid-cols-2 lg:grid-cols-4',
        default => '',
    };
@endphp

@if ($overviewItems)
    <div class="{{ $getBackgroundColourClass() }}">
        @if($title)
            <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">{{ $replaceParameters($title) }}</h2>
        @endif
        <div @class(['grid grid-cols-1 gap-6', $gridColsClass => $gridColsClass !== ''])>
            @foreach($overviewItems as $overviewItem)
                @php
                    /* @var \Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes&Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable $overviewItem */
                @endphp

                <x-flexible-overview-card :title="$overviewItem->getOverviewTitle()"
                                          :description="$overviewItem->getOverviewDescription()"
                                          :image="$overviewItem->getOverviewImageMedia(null, ['class' => 'w-full'])"
                                          :url="$overviewItem->getViewUrl()"></x-flexible-overview-card>
            @endforeach
        </div>
    </div>
@endif
