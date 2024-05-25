@props([
    'assistants'
])

<tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-500" >

@forelse($assistants as $assistant)

    <tr class="odd:bg-white dark:odd:bg-gray-800 even:bg-zinc-100 dark:even:bg-gray-900" >

        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white" >
            {{ $assistant->user->name }}
        </td >

        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white" >
            {{ $assistant->user->email }}
        </td >

        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white" >
            {{ $assistant->trashed() ? 'Desativado' : 'Ativo' }}
        </td >

        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-white flex items-center gap-2 @if($loop->last) rounded-br-lg @endif" >

            <div key="{{ time() . '-edit-component-' . $assistant->id }}" >
                <livewire:assistants.edit
                    :assistant="$assistant"
                    key="{{ time() . '-' . $assistant->id }}"
                />
            </div >

            @if($assistant->trashed())
                <button
                    key="{{ time() . '-restore-' . $assistant->id }}"
                    title="Ativar"
                    wire:click="restore({{ $assistant->id }})"
                    class="text-sm text-white bg-green-800 p-1.5 rounded-full"
                >
                    <x-icons.check class="w-5 h-5" />
                </button >

            @else
                <button
                    key="{{ time() . '-disable-' . $assistant->id }}"
                    title="Desativar"
                    wire:click="disable({{ $assistant->id }})"
                    class="text-sm text-white bg-red-800 p-1.5 rounded-full"
                >
                    <x-icons.trash class="w-5 h-5" />
                </button >
            @endif
        </td >
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
