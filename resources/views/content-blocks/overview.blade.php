<div>
    @if($title)
        <h2>{{$title}}</h2>
    @endif

    @foreach($getOverviewItems() as $overviewItem)
        @php
            /* @var \Statikbe\FilamentFlexibleContentBlocks\Models\Contracts\HasOverviewAttributes $overviewItem */
        @endphp

        {{$overviewItem->getOverviewImageMedia()}}
        <h3>{{$overviewItem->getOverviewTitle()}}</h3>
        <div>{!! $overviewItem->getOverviewDescription() !!}</div>
        <a class="btn" href="#TODO">ga kijken</a>
    @endforeach
</div>
