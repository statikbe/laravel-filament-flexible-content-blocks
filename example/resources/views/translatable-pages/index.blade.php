@php
    /* @var \App\Models\TranslatablePage $page */
@endphp

<x-layouts.base title="{{ $page->getTitle() }}" wide="true">

    <x-flexible-hero :page="$page" />

    <x-flexible-content-blocks :page="$page"/>

</x-layouts.base>
