@props([
    'favoriteOptions',
    'starsOptions',
    'repositoriesOptions',
    'followersOptions'
])

<div
    @click.stop
    x-show="openFilters"
    class="absolute top-0 right-0 z-10 bg-white dark:bg-gray-800 border border-gray-200
                    dark:border-gray-500 rounded-lg shadow-lg p-5 mt-12 w-96"

>
    <div class="mb-4 flex items-center justify-between" >
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" >
            Filtros
        </h3 >

        <span >
            <button
                wire:click="clearFilters"
                @click.stop="openFilters = false"
                class="text-md text-gray-900 hover:text-gray-500 dark:text-gray-300 dark:hover:text-gray-400"
            >
                Limpar
            </button >
        </span >
    </div >

    <div class="flex flex-col gap-2 p-3" >
        <div class="py-2 flex flex-col gap-3 z-100" >

            <x-ts-select.native
                label="Favoritos"
                wire:model.live="favorites"
                select="label:label|value:value"
                :options="$favoriteOptions"
            />

            <x-ts-select.styled
                label="Estrelas"
                placeholder="Selecione a quantidade de estrelas"
                wire:model.live="stars"
                select="label:label|value:value"
                :options="$starsOptions"
            />

            <x-ts-select.styled
                label="Repositórios"
                placeholder="Selecione a quantidade de repositórios"
                wire:model.live="repositories"
                select="label:label|value:value"
                :options="$repositoriesOptions"
            />

            <x-ts-select.styled
                label="Seguidores"
                placeholder="Selecione a quantidade de seguidores"
                wire:model.live="followers"
                select="label:label|value:value"
                :options="$followersOptions"
            />

        </div >
    </div >
</div >
