<x-slot name="header" >
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" >
        {{ __('Assistentes') }}
    </h2 >
</x-slot >

<div class="py-12" >
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" >
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" >

            <div class="flex flex-col items-center sm:items-start sm:flex-row sm:justify-between p-6 gap-8" >

                <h3 class="text-xl text-gray-900 dark:text-gray-100" >
                    {{ __('Listagem de Assistentes') }}
                </h3 >

                <livewire:assistants.create
                    wire:key="{{ time() . 'create-assistant' }}"
                />

            </div >

            <div class="px-6" >
                <hr >
            </div >

            <div class="mx-6 my-12" >

                <div class="text-gray-900 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-500 overflow-x-auto " >

                    <table class="divide-y divide-gray-200 dark:divide-gray-500 w-full rounded-b-lg" >

                        <x-assistants.table.thead />

                        <x-assistants.table.tbody
                            :assistants="$this->assistants"
                        />
                    </table >

                </div >

                <div class="py-4" >
                    {{ $this->assistants->links() }}
                </div >
            </div >

        </div >
    </div >
</div >
