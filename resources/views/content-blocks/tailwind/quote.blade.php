<div class="section section--default">
    <div class="tw-container">
        <blockquote class="tw-pl-6 tw-border-l-2 tw-border-gray-200">
            <div class="tw-text-xl md:tw-text-2xl">
                {!! $replaceParameters($quote) !!}
            </div>

            @if($author)
                <cite class="tw-text-sm">{{$replaceParameters($author)}}</cite>
            @endif
        </blockquote>
    </div>
</div>
