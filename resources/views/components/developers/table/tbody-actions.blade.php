@props([
    'developer',
    'loop'
])

<td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-white flex items-center gap-2 @if($loop->last) rounded-br-lg @endif" >

    <a
        title="Ver perfil no GitHub"
        href="{{ $developer->url }}"
        target="_blank"
        class="text-sm text-white bg-white border border-white rounded-full"
    >
        <x-icons.github />
    </a >

    @if($developer->is_favorite)
        <button
            title="Remover dos favoritos"
            wire:click="unfavoriteDeveloper({{ $developer->id }})"
            class="text-sm text-white bg-red-800 p-1.5 rounded-full"
        >
            <x-icons.heart class="w-3.5 h-3.5" />
        </button >
    @else
        <button
            title="Favoritar"
            wire:click="favoriteDeveloper({{ $developer->id }})"
            class="text-sm text-red-800 bg-white border border-red-800 p-1.5 rounded-full"
        >
            <x-icons.heart class="w-3.5 h-3.5" />
        </button >
    @endif

</td >
