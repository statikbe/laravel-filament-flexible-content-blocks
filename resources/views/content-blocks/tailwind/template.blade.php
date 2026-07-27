@if ($template)
    <div class="content-block content-block--template">
        <div class="container">
            @include($template, ['record' => $record])
        </div>
    </div>
@endif
