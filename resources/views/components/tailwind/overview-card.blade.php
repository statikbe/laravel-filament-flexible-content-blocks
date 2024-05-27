<div class="relative transition duration-300 ease-out bg-white group hover:shadow-md">
    @if($image)
        {!! $image !!}
    @endif
    <div class="p-4 sm:p-6">
        @if($title)
            <a href="{{$url}}" class="before:absolute before:inset-0">
                <h3 class="@if(!$image) mt-0 @endif">{{Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title)}}</h3>
            </a>
        @endif
        @if($description)
            <div>{!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($description) !!}</div>
        @endif
        @if($url)
            <div class="flex justify-end">
                <span class="transition-transform duration-300 ease-out group-hover:translate-x-0.5">&rarr;</span>
            </div>
        @endif
    </div>
</div>
