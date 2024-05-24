@php
    $modalId = 'assistant-edit-modal-' . $record->id;
@endphp

<div class="flex" >
    <a
        title="Editar"
        x-on:click="$modalOpen('{{ $modalId }}')"
        class="text-sm text-white bg-blue-800 p-1.5 rounded-full hover:cursor-pointer"
    >
        <x-icons.pencil-square class="w-5 h-5" />
    </a >

    <x-ts-modal
        id="{{ $modalId }}"
        title="Editar assistente"
        center
    >
        <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" >

            <div class="flex flex-col gap-6" >

                <x-assistants.inputs />

                <x-slot:footer >
                    <div class="flex justify-end gap-3" >
                        <x-ts-button
                            x-on:click="$modalClose('{{ $modalId }}')"
                            color="secondary"
                            outline
                        >
                            {{ __('Cancelar') }}
                        </x-ts-button >

                        <x-ts-button
                            wire:click="save"
                            color="green"
                        >
                            {{ __('Salvar') }}
                        </x-ts-button >
                    </div >
                </x-slot:footer >

            </div >

        </div >
    </x-ts-modal >

</div >
