@props([
    'label',
    'column'
])

<th
    scope="col"
    {{ $attributes->merge(['class' => 'px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-white']) }}
>
    <div class="flex items-center gap-1.5" >

        <span > {{ $label }}</span >

        @if(isset($column))
            @if($column === $this->sortField)

                @if($this->sortDirection === 'asc')
                    <button
                        wire:click="$set('sortDirection', 'desc')"
                        class="text-gray-400 dark:text-gray-300"
                    >
                        <x-icons.bars-arrow-down class="w-3 h-3" />
                    </button >
                @else
                    <button
                        wire:click="$set('sortDirection', 'asc')"
                        class="text-gray-400 dark:text-gray-300"
                    >
                        <x-icons.bars-arrow-up class="w-3 h-3" />
                    </button >
                @endif

            @else
                <button
                    wire:click="$set('sortField', '{{ $column }}');"
                    class="text-gray-400 dark:text-gray-300"
                >
                    <x-icons.arrows-right-left class="w-3 h-3" />
                </button >

            @endif
        @endif
    </div >
</th >
