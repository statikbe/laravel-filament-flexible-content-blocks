<div class="max-w-4xl prose">
    <h2>{{$title}}</h2>
    {!! $content !!}
    {{$getImageMedia(['alt' => 'hewre', 'class'=> 'booyakasha'])}}

    <span>&checkmark; {{$imageTitle}}</span>
    <span>&copy; {{$imageCopyright}}</span>
</div>
