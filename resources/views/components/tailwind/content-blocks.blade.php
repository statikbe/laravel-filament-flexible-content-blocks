@props([
    'class' => '',
])
<div id="content-blocks-wrapper" @class([ $class ])>
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
