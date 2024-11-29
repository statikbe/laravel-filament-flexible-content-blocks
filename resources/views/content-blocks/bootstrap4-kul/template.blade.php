@if ($template)
    <section class="section section--default">
        <div class="container">
            @include($template, ['record' => $record])
        </div>
    </section>
@endif
