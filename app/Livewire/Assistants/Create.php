<?php

namespace App\Livewire\Assistants;

use App\Livewire\Forms\AssistantForm;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Create extends Component
{
    use Interactions;

    public AssistantForm $form;

    public function render(): View
    {
        return view('livewire.assistants.create');
    }

    public function save(): void
    {
        $data = $this->form->validate();

        DB::beginTransaction();

        try {
            $this->form->store($data);

            DB::commit();

            $this->toast()
                ->success('Assistente', 'Assistente criado com sucesso')
                ->send();

            $this->dispatch('modal:assistant-create-modal-close');

            $this->dispatch('assistant::created');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('modal:assistant-create-modal-close');

            $this->toast()
                ->error('Assistente', 'Erro ao criar assistente. Por favor, entre em contato com o suporte.')
                ->send();
        }
    }
}
