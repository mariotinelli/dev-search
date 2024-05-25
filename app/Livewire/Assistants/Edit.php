<?php

namespace App\Livewire\Assistants;

use App\Livewire\Forms\AssistantForm;
use App\Models\Assistant;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Edit extends Component
{
    use Interactions;

    public AssistantForm $form;

    public ?Assistant $assistant = null;

    public function render(): View
    {
        return view('livewire.assistants.edit');
    }

    public function mount(): void
    {
        $this->form->fill([
            'name'  => $this->assistant->user->name,
            'email' => $this->assistant->user->email,
            'cpf'   => $this->assistant->cpf,
        ]);
    }

    public function save(): void
    {
        $data = $this->form->validate();

        DB::beginTransaction();

        try {
            $this->form->update($data, $this->assistant);

            DB::commit();

            $this->toast()
                ->success('Assistente', 'Assistente atualizado com sucesso')
                ->send();

            $this->dispatch('assistant::updated');

            $this->dispatch('modal:assistant-edit-modal-' . $this->assistant->id . '-close');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('modal:assistant-edit-modal-' . $this->assistant->id . '-close');

            $this->toast()
                ->error('Assistente', 'Erro ao atualizar assistente. Por favor, entre em contato com o suporte.')
                ->send();
        }
    }
}
