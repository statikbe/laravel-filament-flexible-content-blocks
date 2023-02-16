<div class="card">
    @if($image)
        {!! $image !!}
    @endif
    @if($title)
        <h3>
            <a href="{{$titleUrl}}">{{$title}}</a>
        </h3>
    @endif
    @if($description)
        <div>{!! $description !!}</div>
    @endif
    @if($callToActions)
        @foreach($callToActions as $callToAction)
            <x-flexible-call-to-action :data="$callToAction"></x-flexible-call-to-action>
        @endforeach
    @endif
</div>
