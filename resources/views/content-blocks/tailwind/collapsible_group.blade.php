<div @class([
    'content-block content-block--collapsible-group',
    $getBackgroundColourClass(),
])>
    <div class="container">
        <div class="flex flex-col items-start gap-4 md:gap-6">
            @if ($groupTitle)
                <h2>{{ $replaceParameters($groupTitle) }}</h2>
            @endif

            @if ($groupIntro)
                <div class="text-base canBeRichEditorContent">
                    {!! $replaceParameters($groupIntro) !!}
                </div>
            @endif

            <ul class="bg-white rounded-xl border border-gray-300 w-full">
                @foreach($collapsibleItems as $collapsibleItem)
                    <li class="border-t first:border-t-0 border-gray-300 px-6 md:px-10 py-4 md:py-6 flex flex-col gap-4"
                        x-data="{ isCollapsed: {{ $collapsibleItem->isOpenByDefault ? 'false' : 'true' }} }"
                    >
                        <div class="flex flex-row justify-between items-center group"
                             x-on:click="isCollapsed = !isCollapsed"
                        >
                            <span class="text-gray-800 group-hover:text-primary text-lg font-semibold w-full group-hover:cursor-pointer">
                                {{ $collapsibleItem->title }}
                            </span>

                            <button type="button"
                                    class="group-hover:[&>svg]:fill-primary"
                            >
                                <x-heroicon-c-chevron-up class="w-6 fill-gray-600"
                                                         x-bind:class="isCollapsed ? 'rotate-180' : ''"
                                />
                            </button>
                        </div>

                        <div x-show="!isCollapsed"
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
