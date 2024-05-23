<div class="section section--default">
    <div class="container">
        <blockquote class="pl-6 border-l-2 border-gray-200">
            <div class="text-xl md:text-2xl">
                {!! $replaceParameters($quote) !!}
            </div>

            @if($author)
                <footer class="text-sm">{{$replaceParameters($author)}}</footer>
            @endif
        </blockquote>
    </div>
</div>
