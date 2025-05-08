<div>
    @if($stylesheet)
        <style scoped>
            @import '{{$stylesheet}}';
        </style>
    @endif

    {!! Blade::renderComponent($component) !!}
</div>

