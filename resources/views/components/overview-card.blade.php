<div class="card">
    @if($image)
        {!! $image !!}
    @endif
    @if($title)
        <h3>
            <a href="{{$url}}">{{$title}}</a>
        </h3>
    @endif
    @if($description)
        <div>{!! $description !!}</div>
    @endif
    @if($url)
        <a class="btn btn-link btn-primary" href="{{$url}}">&rarr;</a>
    @endif
</div>
