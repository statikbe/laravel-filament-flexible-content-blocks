@if($template)
    <div class="section section--default">
        <div class="container">
            @include($template, ['record' => $record])
        </div>
    </div>
@endif
