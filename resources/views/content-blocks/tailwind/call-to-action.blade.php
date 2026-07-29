<div @class([
    'content-block content-block--call-to-action',
    $getBackgroundColourClass(),
])>
    <div class="container">
        <div @class([
            'rounded-xl overflow-hidden',
            'bg-white' => $getBackgroundColourClass == 'section--light',
            'bg-light' => $getBackgroundColourClass != 'section--light',
        ])>
            <div @class([
                'grid grid-cols-1 gap-6',
                'sm:grid-cols-3 md:grid-cols-5' => $hasImage(),
                'justify-items-center' => !$hasImage(),
            ])>
                @if ($hasImage())
                    <div class="relative md:col-span-2 min-h-40">
                        <div class="absolute inset-0">
                            {{ $getImageMedia([
                                'class' => 'w-full h-full object-cover object-center',
                                'loading' => 'lazy',
                            ]) }}
                        </div>

                        @if ($imageCopyright)
                            <small>&copy; {{ $imageCopyright }}</small>
                        @endif
                    </div>
                @endif

                <div @class([
                    'p-6 flex flex-col gap-4 md:gap-8 text-center',
                    'items-center sm:col-span-2 md:col-span-3 sm:items-start sm:text-left' => $hasImage(),
                    'items-center md:w-3/4' => !$hasImage(),
                ])>
                    @if ($title)
                        <h2 class="text-balance">{{ $replaceParameters($title) }}</h2>
                    @endif

                    @if ($text)
                        <div class="text-base canBeRichEditorContent">
                            {!! $replaceParameters($text) !!}
                        </div>
                    @endif

                    @if ($callToActions)
                        <div @class([
                            'flex flex-wrap items-center gap-x-4 gap-y-2 mt-4',
                        ])>
                            @foreach ($callToActions as $callToAction)
                                <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
