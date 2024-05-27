@props([
    'developers'
])

<tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-500" >

@forelse($developers as $developer)

    <tr class="odd:bg-white dark:odd:bg-gray-800 even:bg-zinc-100 dark:even:bg-gray-900" >

        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white" >
            {{ $developer->name }}
        </td >

        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white" >
            {{ $developer->email }}
        </td >

        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white" >
            {{ $developer->followers }}
        </td >

        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white" >
            {{ $developer->stars }}
        </td >

        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white" >
            {{ $developer->repos }}
        </td >

        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 dark:text-white" >
            {{ $developer->score }}
        </td >

        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-white flex items-center gap-2 @if($loop->last) rounded-br-lg @endif" >

            <a
                title="Ver perfil no GitHub"
                href="{{ $developer->html_url }}"
                target="_blank"
                class="text-sm text-white bg-white-800 p-1.5 rounded-full"
            >
                <x-icons.github />
            </a >

            @if($developer->is_favorite)
                <button
                    title="Favoritar"
                    wire:click="unfavoriteDeveloper({{ $developer->id }})"
                    class="text-sm text-white bg-red-800 p-1.5 rounded-full"
                >
                    <x-icons.x-mark class="w-3.5 h-3.5" />
                </button >
            @else
                <button
                    title="Favoritar"
                    wire:click="favoriteDeveloper({{ $developer->id }})"
                    class="text-sm text-white bg-red-800 p-1.5 rounded-full"
                >
                    <x-icons.heart class="w-3.5 h-3.5" />
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
