<div class="space-y-4">
    @if ($groupTitle)
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $replaceParameters($groupTitle) }}</h2>
    @endif

    @if ($groupIntro)
        <div class="prose max-w-none dark:prose-invert">
            {!! $replaceParameters($groupIntro) !!}
        </div>
    @endif

    <div class="space-y-2">
        @foreach($collapsibleItems as $collapsibleItem)
            <x-filament::section
                :collapsible="true"
                :collapsed="!$collapsibleItem->isOpenByDefault"
            >
                <x-slot name="heading">{{ $collapsibleItem->title }}</x-slot>

                <div class="prose max-w-none dark:prose-invert">
                    {!! $collapsibleItem->text !!}
                </div>
            </x-filament::section>
        @endforeach
    </div>
</div>
