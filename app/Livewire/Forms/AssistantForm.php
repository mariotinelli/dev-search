<?php

namespace App\Livewire\Forms;

use App\Enums\RoleEnum;
use App\Mail\SendPasswordToNewUserMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Form;
use TallStackUi\Traits\Interactions;

class AssistantForm extends Form
{
    use Interactions;

    #[Validate(['required', 'max:255'])]
    public ?string $name = null;

    #[Validate(['required', 'email', 'unique:users,email'])]
    public ?string $email = null;

    #[Validate(['required', 'cpf', 'unique:assistants,cpf'])]
    public ?string $cpf = null;

    public function store(): void
    {
        $data = $this->validate();

        DB::beginTransaction();

        try {
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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->toast()
                ->error('Erro', 'Ocorreu um erro ao criar o assistente. Por favor, contate o suporte.')
                ->send();

            return;
        }
    }

}