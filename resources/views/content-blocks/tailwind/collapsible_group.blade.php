@use(Illuminate\Support\Str)

<div @class([
    'content-block content-block--collapsible-group',
    $getBackgroundColourClass(),
])>
    <div class="container">
        <div class="flex flex-col items-start gap-4 md:gap-6">
            @if ($groupTitle)
                <h2 class="text-balance">{{ $replaceParameters($groupTitle) }}</h2>
            @endif

            @if ($groupIntro)
                <div class="text-base canBeRichEditorContent">
                    {!! $replaceParameters($groupIntro) !!}
                </div>
            @endif

            <ul class="bg-white rounded-xl border border-gray-300 w-full">
                @foreach($collapsibleItems as $collapsibleItem)
                    @php
                        $panelId = 'collapsible-' . (Str::slug($collapsibleItem->title) ?: 'item') . '-' . $loop->index;
                    @endphp

                    <li class="border-t first:border-t-0 border-gray-300 px-6 md:px-10 py-4 md:py-6 flex flex-col gap-4"
                        x-data="{ isCollapsed: {{ $collapsibleItem->isOpenByDefault ? 'false' : 'true' }} }"
                    >
                        <button type="button"
                                class="flex flex-row justify-between items-center group w-full text-left gap-4"
                                aria-controls="{{ $panelId }}"
                                x-bind:aria-expanded="!isCollapsed"
                                x-on:click="isCollapsed = !isCollapsed"
                        >
                            <span class="text-gray-800 group-hover:text-primary text-lg font-semibold">{{ $collapsibleItem->title }}</span>

                            <x-heroicon-c-chevron-up class="w-6 shrink-0 fill-gray-600 group-hover:fill-primary"
                                                     x-bind:class="isCollapsed ? 'rotate-180' : ''"
                            />
                        </button>

                        <div id="{{ $panelId }}"
                             x-show="!isCollapsed"
                             x-cloak
                        >
                            <div class="border-t border-gray-300 py-4 text-lg text-gray-600 canBeRichEditorContent">
                                {!! $collapsibleItem->text !!}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
