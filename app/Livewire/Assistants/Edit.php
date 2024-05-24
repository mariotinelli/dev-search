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

    public ?Assistant $record = null;

    public function render(): View
    {
        return view('livewire.assistants.edit');
    }

    public function mount(): void
    {
        $this->form->setAssistant($this->record);
    }

    public function save(): void
    {
        $data = $this->form->validate();

        DB::beginTransaction();

        try {
            $this->form->update($data);

            DB::commit();

            $this->toast()
                ->success('Assistente', 'Assistente atualizado com sucesso')
                ->send();

            $this->dispatch('modal:assistant-edit-modal-' . $this->record->id . '-close');

            $this->dispatch('assistant::updated');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('modal:assistant-edit-modal-' . $this->record->id . '-close');

            $this->toast()
                ->error('Assistente', 'Erro ao atualizar assistente. Por favor, entre em contato com o suporte.')
                ->send();
        }
    }
}
