<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
    @class([
        'drop-in-action-component',
        'w-full h-full flex items-end justify-between' => $isLabelHidden() && ! $hasInlineLabel()
    ])
>
    <div
        class="drop-in-action-actions-container relative"
        @if ($isLabelHidden() && ! $hasInlineLabel())
            style="padding-block-end: 1px;"
        @endif
    >
        @foreach ($getExecutableActions() as $executableAction)
            <x-forms::actions.action
                :action="$executableAction"
                class="flex items-center"
                component="forms::button"
            >
                @if (!$executableAction->isLabelHidden())
                    {{ $executableAction->getLabel() }}
                @endif
            </x-forms::actions.action>
        @endforeach
    </div>
</x-forms::field-wrapper>
