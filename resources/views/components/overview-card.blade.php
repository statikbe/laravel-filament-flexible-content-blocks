<div class="relative transition-all duration-300 ease-out bg-white group hover:shadow-lg">
    @if($image)
        {!! $image !!}
    @endif
    <div class="p-4 prose max-w-none sm:p-6">
        @if($title)
            <a href="{{$url}}" class="no-underline before:absolute before:inset-0">
                <h3 @if(!$image)class="mt-0"@endif>{{$title}}</h3>
            </a>
        @endif
        @if($description)
            <div>{!! $description !!}</div>
        @endif
        @if($url)
            <span class="btn btn-link btn-primary">&rarr;</span>
        @endif
    </div>
</div>
