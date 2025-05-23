@if ($title)
    <div class="card card-primary">
        <h2>{{ $replaceParameters($title) }}</h2>
    </div>
@endif


<section class="row">
    @foreach ($cards as $card)
        @php
            /* @var \Statikbe\FilamentFlexibleCgroup-hover:tw-bg-red-500ontentBlocks\Filament\Form\Fields\Blocks\Data\CardData $card */

            $backgroundClass = $getBackgroundColourClass();
        @endphp

        <x-flexible-card 
            :backgroundClass="$backgroundClass" 
            :data="$card" 
            @class([
                'col-xs-24 col-md-12' => $gridColumns == 1,
                'col-xs-12 col-md-6' => $gridColumns == 2,
                'col-xs-8 col-md-4' => $gridColumns == 3,
                'col-xs-6 col-md-3' => $gridColumns == 4,
            ]) 
        />
    @endforeach
</section>
