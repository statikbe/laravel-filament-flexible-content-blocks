@if($template)
    <div class="py-12">
        <div class="container">
            @include($template, ['record' => $record])
        </div>
    </div>
@endif
