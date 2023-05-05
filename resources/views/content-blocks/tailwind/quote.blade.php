<div class="py-12 section section--default">
    <div class="container px-4 mx-auto">
        <blockquote class="pl-6 border-l-2 border-gray-200 quote">
            <div class="text-2xl">
                {!! $replaceParameters($quote) !!}
            </div>

            @if($author)
                <footer class="quote__footer"><small>{{$replaceParameters($author)}}</small></footer>
            @endif
        </blockquote>
    </div>
</div>
