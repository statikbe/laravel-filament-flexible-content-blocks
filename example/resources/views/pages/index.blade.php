@php
    /* @var \App\Models\Page $page */
@endphp

<x-layouts.base title="{{ $page->title }}" wide="true">

    <x-flexible-hero :page="$page" />

    <x-flexible-content-blocks :page="$page"/>

</x-layouts.base>
