<div class="content">
    @if(is_array($contentBlocks))
        @foreach($contentBlocks ?? [] as $block)
            {{$block->render()}}
            <x-dynamic-component component="content-blocks.{{ $block['type'] }}" :block-data="$block['data']"/>
        @endforeach
    @endif
</div>
