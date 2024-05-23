<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Assistentes') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

            <div class="flex justify-between p-6">

                <h3 class="text-xl text-gray-900 dark:text-gray-100">
                    {{ __('Listagem de Assistentes') }}
                </h3>

                <a
                    href="{{ route('assistants.create') }}"
                   class="text-sm text-gray-600 dark:text-gray-200 hover:text-gray-900 dark:hover:text-gray-100 dark:hover:bg-gray-600
                           bg-gray-900 dark:bg-gray-800 py-2 px-4 rounded-xl border border-gray-900 dark:border-gray-100"
                >
                    {{ __('Cadastrar novo assistente') }}
                </a>

            </div>

            <div class="px-6"> <hr> </div>

            <div class="mx-6 my-12">
                <x-table
                    :columns="[
                        ['label' => 'Nome', 'column' => 'user.name'],
                        ['label' => 'Email', 'column' => 'user.email'],
                        ['label' => 'Cpf', 'column' => 'cpf'],
                        ['label' => 'Situação', 'column' => 'situation'],
                    ]"
                    :records="$this->assistants"
                    routeEdit="assistants.edit"
                />
            </div>

        </div>
    </div>
</div>
