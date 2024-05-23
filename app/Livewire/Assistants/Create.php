<?php

namespace App\Livewire\Assistants;

use App\Livewire\Forms\AssistantForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Create extends Component
{
    use Interactions;

    public AssistantForm $form;

    public function render(): View
    {
        return view('livewire.assistants.create')
            ->layout('layouts.app');
    }

    public function save(): RedirectResponse|Redirector
    {
        $this->form->store();

        $this->toast()
            ->success('Assistente', 'Assistente criado com sucesso')
            ->flash()
            ->send();

        return to_route('assistants.index');
    }
}
