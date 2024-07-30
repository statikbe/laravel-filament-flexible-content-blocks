<article class="tw-relative tw-transition tw-duration-300 tw-ease-out tw-bg-white tw-group hover:tw-shadow-md">
    @if($image)
        {!! $image !!}
    @endif
    <div class="tw-p-4 sm:tw-p-6">
        @if($title)
            <a href="{{$url}}" class="before:tw-absolute before:tw-inset-0">
                <h3 class="@if(!$image) tw-mt-0 @endif">{{Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($title)}}</h3>
            </a>
        @endif
        @if($description)
            <div>{!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($description) !!}</div>
        @endif
        @if($url)
            <div class="tw-flex tw-justify-end">
                <span class="tw-transition-transform tw-duration-300 tw-ease-out group-hover:tw-translate-x-0.5" aria-hidden="true">&rarr;</span>
            </div>
        @endif
    </div>
</article>
