@php
    /* @var \App\Models\TranslatablePage $page */
@endphp

<x-layouts.flexible title="{{ $page->title }}" wide="true">

    <x-flexible-hero :page="$page" />

    <div>
        <x-flexible-content-blocks :page="$page">
        </x-flexible-content-blocks>
    </div>
</x-layouts.flexible>
