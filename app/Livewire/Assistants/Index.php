<?php

namespace App\Livewire\Assistants;

use App\Models\Assistant;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use TallStackUi\Traits\Interactions;

class Index extends Component
{
    use WithPagination;
    use Interactions;

    protected $listeners = [
        'assistant::created' => '$refresh'
    ];

    public function render(): View
    {
        return view('livewire.assistants.index')
            ->layout('layouts.app');
    }

    #[Computed]
    public function assistants(): LengthAwarePaginator
    {
        return Assistant::query()
            ->with(['user'])
            ->withTrashed()
            ->latest()
            ->orderBy('deleted_at')
            ->paginate(10);
    }

    public function disable(int $id): void
    {
        $this->dialog()
            ->question('Atenção!', 'Tem certeza de que deseja desativar este assistente?')
            ->confirm('Sim', 'confirmedDisable', $id)
            ->cancel('Não')
            ->send();
    }

    public function restore(int $id): void
    {
        $this->dialog()
            ->question('Atenção!', 'Tem certeza de que deseja ativar este assistente?')
            ->confirm('Sim', 'confirmedRestore', $id)
            ->cancel('Não')
            ->send();
    }

    public function confirmedDisable(int $id): void
    {
        Assistant::query()->withTrashed()->findOrFail($id)->delete();

        $this->toast()
            ->success('Assistente', 'Assistente desativado com sucesso.')
            ->send();

        $this->dispatch('$refresh');
    }

    public function confirmedRestore(int $id): void
    {
        Assistant::query()->withTrashed()->findOrFail($id)->restore();

        $this->toast()
            ->success('Assistente', 'Assistente ativado com sucesso.')
            ->send();

        $this->dispatch('$refresh');
    }
}
