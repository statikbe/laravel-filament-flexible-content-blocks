<template shadowrootmode="open">
    @if($stylesheet)
        <style>
            @import '{{$stylesheet}}';
        </style>
    @endif

    {!! Blade::renderComponent($component) !!}
</template>
