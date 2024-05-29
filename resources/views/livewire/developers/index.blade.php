<x-slot name="header" >
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" >
        {{ __('Análise de desenvolvedores') }}
    </h2 >
</x-slot >

<div class="py-12"
     x-data="{ openFilters: false }" >
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" >
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6" >

            <div class="flex items-center justify-between" >
                <h3 class="text-xl text-gray-900 dark:text-gray-100" >
                    {{ __('Lista de Desenvolvedores') }}
                </h3 >

                <div class="flex items-center gap-2 relative" >
                    <x-ts-input
                        placeholder="Busque um desenvolvedor"
                        class="w-72"
                        wire:model.live="search"
                        icon="magnifying-glass"
                    />

                    <x-icons.funnel
                        @click.outside="openFilters = false"
                        x-on:click="openFilters = !openFilters"
                        class="w-6 h-6 text-gray-500 hover:cursor-pointer"
                    />

                    <x-developers.table.filters
                        :favoriteOptions="$this->favoriteOptions"
                        :starsOptions="$this->starsOptions"
                        :repositoriesOptions="$this->repositoriesOptions"
                        :followersOptions="$this->followersOptions"
                    />
                </div >
            </div >

            <div class="my-8" >

                <div class="text-gray-900 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-500 overflow-x-auto " >

                    <table class="divide-y divide-gray-200 dark:divide-gray-500 w-full rounded-b-lg" >

                        <x-developers.table.thead />

                        <x-developers.table.tbody
                            :developers="$this->developers"
                        />
                    </table >

                </div >

                <div class="py-4" >
                    {{ $this->developers->links() }}
                </div >
            </div >

        </div >
    </div >
</div >
