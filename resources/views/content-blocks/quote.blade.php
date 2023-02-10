<div class="py-20 section section--default">
    <div class="container px-4 mx-auto">
        <blockquote class="pl-6 border-l-2 border-gray-200">
            <div class="text-2xl">
                {!! $quote !!}
            </div>
            
            @if($author)
                <footer><small>{{$author}}</small></footer>
            @endif
        </blockquote>
    </div>
</div>