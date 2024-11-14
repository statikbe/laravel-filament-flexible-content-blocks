<section class="card {{ $getBackgroundColourClass() }}">
    <div class="card-body">
        <blockquote class="blockquote">
            {!! $replaceParameters($quote) !!}

            @if ($author)
                <footer class="blockquote-footer">{{ $replaceParameters($author) }}</footer>
            @endif
        </blockquote>
    </div>
</section>
