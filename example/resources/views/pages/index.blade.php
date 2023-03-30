@php
    /* @var \App\Models\Page $page */
@endphp

<x-layouts.base title="{{ $page->title }}" wide="true">
    <x-flexible-hero :page="$page" />

    <div class="prose content">
        <x-flexible-content-blocks :page="$page"/>
    </div>
</x-layouts.base>
