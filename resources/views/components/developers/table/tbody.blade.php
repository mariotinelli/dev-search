@props([
    'developers'
])

<tbody class="bg-white divide-y divide-gray-200 dark:divide-gray-500" >

@forelse($developers as $developer)

    <tr class="odd:bg-white dark:odd:bg-gray-800 even:bg-zinc-100 dark:even:bg-gray-900" >

        <x-developers.table.tbody-actions
            :developer="$developer"
            :loop="$loop"
        />

        <x-developers.table.tbody-item :value="$developer->name" />
        <x-developers.table.tbody-item :value="$developer->email" />
        <x-developers.table.tbody-item :value="$developer->score" />
        <x-developers.table.tbody-item :value="$developer->followers" />
        <x-developers.table.tbody-item :value="$developer->stars" />
        <x-developers.table.tbody-item :value="$developer->repos" />
        <x-developers.table.tbody-item :value="$developer->commits" />
        <x-developers.table.tbody-item :value="$developer->repos_contributions" />

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
