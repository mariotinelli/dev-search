<x-slot name="header" >
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" >
        {{ __('Desenvolvedores') }}
    </h2 >
</x-slot >

<div class="py-12" >
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" >
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6" >

            <h3 class="text-xl text-gray-900 dark:text-gray-100" >
                {{ __('Análise de desenvolvedores') }}
            </h3 >

            <div class="py-6" >
                <hr >
            </div >

            <div class="flex flex-col gap-8" >
                <h3 class="text-xl text-gray-900 dark:text-gray-100" >
                    {{ __('Filtros') }}
                </h3 >

                <div class="py-2 grid grid-cols-4 gap-3 z-100" >

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
                        :options="$starOptions"
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

            <div class="py-6" >
                <hr >
            </div >

            <h3 class="text-xl text-gray-900 dark:text-gray-100" >
                {{ __('Desenvolvedores encontrados') }}
            </h3 >

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
