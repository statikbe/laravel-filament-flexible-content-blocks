<div class="content">
    @if(is_array($contentBlocks))
        @foreach($contentBlocks ?? [] as $block)
            <x-dynamic-component component="content-blocks.{{ $block['type'] }}" :block-data="$block['data']"/>
        @endforeach
    @endif
</div>
