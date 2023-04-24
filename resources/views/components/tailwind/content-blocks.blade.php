<div>
    @if(is_array($contentBlocks))
        @foreach($contentBlocks ?? [] as $block)
            {{$block->render()->with($block->data())}}
        @endforeach
    @endif
</div>
