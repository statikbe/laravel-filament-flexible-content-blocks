@props([
    'class' => '',
])
<div id="content-blocks-wrapper" @class([ 'flex flex-col gap-6', $class ])>
    @if(is_array($contentBlocks))
        @foreach($contentBlocks ?? [] as $block)
            {{
                $block->withAttributes($attributes->getAttributes())
                    ->render()
                    ->with($block->data())
            }}
        @endforeach
    @endif
</div>
