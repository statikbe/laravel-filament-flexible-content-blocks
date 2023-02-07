<div class="space-y-2">
    <h1 class="text-2xl font-bold tracking-tight">
        {{ $title }}
    </h1>

    <div>
        {!! $intro !!}
    </div>

    <div>
        {{$getHeroImageMedia()}}
        <span>&checkmark; {{$heroImageTitle}}</span>
        <span>&copy; {{$heroImageCopyright}}</span>
    </div>
</div>
