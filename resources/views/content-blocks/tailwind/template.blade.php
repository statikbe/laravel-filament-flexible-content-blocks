@if($template)
    <div class="section section--default">
        <div class="tw-container">
            @include($template, ['record' => $record])
        </div>
    </div>
@endif
