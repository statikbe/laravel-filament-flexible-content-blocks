<div>
    @if($title)
        <h2>{{$title}}</h2>
    @endif
    <div class="grid grid-cols-3 gap-4">
    @foreach($getOverviewItems() as $overviewItem)
        @php
            /* @var \Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes&Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\Linkable $overviewItem */
        @endphp

        <div class="card">
            {{$overviewItem->getOverviewImageMedia()}}
            <h3>
                <a href="{{$overviewItem->getViewUrl()}}">{{$overviewItem->getOverviewTitle()}}</a>
            </h3>
            <div>{!! $overviewItem->getOverviewDescription() !!}</div>
            <a class="btn btn-link btn-primary" href="{{$overviewItem->getViewUrl()}}">&rarr;</a>
        </div>
    @endforeach
    </div>
</div>
