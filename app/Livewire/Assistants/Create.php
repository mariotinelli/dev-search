<?php

namespace App\Livewire\Assistants;

use App\Livewire\Forms\AssistantForm;
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
        $this->form->store();

        $this->toast()
            ->success('Assistente', 'Assistente criado com sucesso')
            ->send();

        $this->dispatch('modal:assistant-create-modal-close');

        $this->dispatch('assistant::created');
    }
}
