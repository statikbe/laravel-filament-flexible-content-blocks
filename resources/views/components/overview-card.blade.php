<div class="relative transition-all duration-300 ease-out bg-white group hover:shadow-md">
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
            <div class="flex justify-end">
                <span class="transition-transform duration-300 ease-out group-hover:translate-x-0.5">&rarr;</span>
            </div>
        @endif
    </div>
</div>
