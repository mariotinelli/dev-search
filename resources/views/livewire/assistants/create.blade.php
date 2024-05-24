<div >
    <a
        x-on:click="$modalOpen('assistant-create-modal')"
        class="text-sm text-gray-600 dark:text-gray-200 hover:bg-gray-100 hover:text-gray-900
                dark:hover:text-gray-100 dark:hover:bg-gray-600 bg-white dark:bg-gray-800
                py-2 px-4 rounded-xl border border-gray-300 dark:border-gray-100 hover:cursor-pointer"
    >
        {{ __('Cadastrar novo assistente') }}
    </a >


    <x-ts-modal
        id="assistant-create-modal"
        title="Cadastrar assistente"
        center
    >
        <div class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" >

            <div class="flex flex-col gap-6" >

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
                        x-mask="999.999.999-99"
                        label="CPF *"
                        placeholder="CPF do assistente"
                        wire:model="form.cpf"
                    />
                </div >

                <x-slot:footer >
                    <div class="flex justify-end gap-3" >
                        <x-ts-button
                            x-on:click="$modalClose('assistant-create-modal')"
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
