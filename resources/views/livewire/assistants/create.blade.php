<x-slot name="header" >
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" >
        {{ __('Cadastrar Assistente') }}
    </h2 >
</x-slot >

<div class="py-12" >
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" >
        <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" >

            <form
                class="flex flex-col gap-6"
                wire:submit.prevent="save"
            >

                <x-ts-input
                    label="Nome *"
                    placeholder="Nome do assistente"
                    wire:model="form.name"
                />

                <div class="grid grid-cols-2 gap-2" >
                    <x-ts-input
                        label="E-mail *"
                        placeholder="E-mail do assistente"
                        wire:model="form.email"
                    />

                    <x-ts-input
                        label="CPF *"
                        placeholder="CPF do assistente"
                        wire:model="form.cpf"
                    />
                </div >

                <div class="flex justify-end" >
                    <x-ts-button
                        color="green"
                    >
                        {{ __('Salvar') }}
                    </x-ts-button >
                </div >

            </form >

        </div >
    </div >
</div >
