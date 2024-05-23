@props([
    'records',
    'columns',
    'routeEdit',
    'hasActions' => true
])

<div class="text-gray-900 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-500 overflow-x-auto " >

    <table class="divide-y divide-gray-200 dark:divide-gray-500 w-full rounded-b-lg" >

        <thead class="bg-gray-50 dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-500" >
        <tr >

            @foreach($columns as $column)
                <th
                    scope="col"
                    class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white"
                >
                    {{ $column['label'] }}
                </th >
            @endforeach

            @if($hasActions)
                <th
                    scope="col"
                    class="relative sm:pr-6 text-center text-sm font-semibold text-gray-900 dark:text-white w-32"
                >
                    <span >Ações</span >
                </th >
            @endif
        </tr >
        </thead >

        <tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-500" >

        @forelse($records as $record)

            <tr class="odd:bg-white dark:odd:bg-gray-800 even:bg-zinc-100 dark:even:bg-gray-900" >

                @foreach($columns as $column)
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white" >
                        {{ data_get($record, $column['column']) }}
                    </td >
                @endforeach

                @if($hasActions)
                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-white flex items-center gap-2 @if($loop->last) rounded-br-lg @endif" >
                        <a
                            href="{{ route($routeEdit, $record->id) }}"
                            class="text-sm text-white bg-blue-800 p-1.5 rounded-full"
                        >
                            <x-icons.pencil-square class="w-5 h-5" />
                        </a >

                        @if(method_exists($record, 'trashed'))
                            @if($record->trashed())
                                <button
                                    title="Ativar usuário"
                                    wire:click="restore({{ $record->id }})"
                                    class="text-sm text-white bg-green-800 p-1.5 rounded-full"
                                >
                                    <x-icons.check class="w-5 h-5" />
                                </button >

                            @else
                                <button
                                    title="Desativar usuário"
                                    wire:click="disable({{ $record->id }})"
                                    class="text-sm text-white bg-red-800 p-1.5 rounded-full"
                                >
                                    <x-icons.trash class="w-5 h-5" />
                                </button >
                            @endif
                        @endif
                    </td >
                @endif
            </tr >

        @empty

            <tr class="bg-white dark:bg-gray-800" >
                <td colspan="4"
                    class="px-6 py-4 text-sm text-gray-500 dark:text-white" >
                    Nenhum registro encontrado.
                </td >
            </tr >

        @endforelse
        </tbody >
    </table >

</div >

<div class="py-4" >
    {{ $records->links() }}
</div >
