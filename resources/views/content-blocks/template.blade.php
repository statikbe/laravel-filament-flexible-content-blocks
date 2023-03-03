@if($template)
    <div>
        @include($template, ['record' => $record])
    </div>
@endif
