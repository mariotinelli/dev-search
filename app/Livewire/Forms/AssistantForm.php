<?php

namespace App\Livewire\Forms;

use App\Enums\RoleEnum;
use App\Mail\SendPasswordToNewUserMail;
use App\Models\Assistant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Form;
use TallStackUi\Traits\Interactions;

class AssistantForm extends Form
{
    use Interactions;

    public ?Assistant $assistant = null;

    public ?string $name = null;

    public ?string $email = null;

    public ?string $cpf = null;

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->assistant?->user?->id)],
            'cpf' => ['required', 'cpf', Rule::unique('assistants')->ignore($this->assistant?->id)],
        ];
    }

    public function setAssistant(Assistant $assistant): void
    {
        $this->assistant = $assistant;

        $this->fill([
            'name' => $assistant->user->name,
            'email' => $assistant->user->email,
            'cpf' => $assistant->cpf
        ]);
    }

    public function store(array $data): void
    {
        $newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => RoleEnum::ASSISTANT,
            'password' => once(fn(): string => Hash::make('password')),
        ]);

        $newUser->assistant()->create($data);

        Mail::to($newUser->email)
            ->send(new SendPasswordToNewUserMail(once(fn(): string => Hash::make('password'))));

        $this->reset();
    }

    public function update(array $data): void
    {
        $this->assistant->user()->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $this->assistant->update($data);
    }

}
