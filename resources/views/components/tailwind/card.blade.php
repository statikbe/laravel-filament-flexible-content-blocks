@php
    /* @var \Statikbe\FilamentFlexibleContentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */
@endphp

<article class="bg-white flex flex-col justify-start items-start relative transition duration-300 ease-out group @if ($isFullyClickable()) hover:shadow-md @endif">
    @if (!$slot->isEmpty() || $card->hasImage())
        <div aria-hidden="true"
             class="px-2 pt-2 w-full [&>img]:w-full [&>img]:object-cover [&>img]:object-center"
        >
            @if (!$slot->isEmpty())
                {{-- Image slot --}}
                {{ $slot }}
            @elseif($card->hasImage())
                @if ($card->imageHtml)
                    {!! $card->imageHtml !!}
                @elseif($card->imageUrl)
                    <img src="{{ $card->imageUrl }}" class="w-full"
                         @if ($card->title) alt="{{ Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($card->title) }}" @endif />
                @endif
            @endif
        </div>
    @endif

    <div class="flex flex-col justify-start items-start gap-y-4 p-4 md:p-6">
        @if ($card->title)
            <h3>
                @if ($getTitleUrl())
                    <a href="{{ $getTitleUrl() }}">
                @endif
                {{ $card->title }}
                @if ($getTitleUrl())
                    </a>
                @endif
            </h3>
        @endif

        @if ($card->text)
            <div class="canBeRichEditorContent">{!! Statikbe\FilamentFlexibleContentBlocks\FilamentFlexibleContentBlocks::replaceParameters($card->text) !!}</div>
        @endif

        @if ($card->callToActions)
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-4">
                @foreach ($card->callToActions as $callToAction)
                    <x-flexible-call-to-action :data="$callToAction" :isFullyClickable="$isFullyClickable()"></x-flexible-call-to-action>
                @endforeach
            </div>
        @endif
    </div>
</article>
