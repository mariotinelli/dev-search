<?php

use App\Livewire\Assistants;
use App\Models\{Assistant, User};
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->loggedUser = User::factory()->create();

    $this->assistant = Assistant::factory()->create();

    $this->actingAs($this->loggedUser);
});

it('should be edit a assistant', function () {

    // Arrange
    $newUserData = User::factory()->make();

    $newAssistantData = Assistant::factory()->make([
        'deleted_at' => null,
    ]);

    // Act
    $lw = livewire(Assistants\Edit::class, ['assistant' => $this->assistant])
        ->set('form', [
            'name' => $newUserData->name,
            'email' => $newUserData->email,
            'cpf' => $newAssistantData->cpf,
        ])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertDispatched('modal:assistant-edit-modal-' . $this->assistant->id . '-close')
        ->assertDispatched('assistant::updated');

    assertDatabaseHas('users', [
        'name' => $newUserData->name,
        'email' => $newUserData->email,
    ]);

    assertDatabaseHas('assistants', [
        'cpf' => $newAssistantData->cpf,
        'user_id' => User::whereEmail($newUserData->email)->first()->id,
    ]);

});

it('should be fill assistant data with correct values', function () {

    // Act
    $lw = livewire(Assistants\Edit::class, ['assistant' => $this->assistant]);

    // Assert
    $lw->assertSet('form.name', $this->assistant->user->name)
        ->assertSet('form.email', $this->assistant->user->email)
        ->assertSet('form.cpf', $this->assistant->cpf);

});

describe('name is', function () {

    test('required', function () {

        // Act
        $lw = livewire(Assistants\Edit::class, ['assistant' => $this->assistant])
            ->set('form', [
                'name' => null,
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.name' => 'required']);

    });

    test('cannot has more than 255 characters', function () {

        // Act
        $lw = livewire(Assistants\Edit::class, ['assistant' => $this->assistant])
            ->set('form', [
                'name' => str_repeat('a', 256),
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.name' => 'max']);

    });

});

describe('email is', function () {

    test('required', function () {

        // Act
        $lw = livewire(Assistants\Edit::class, ['assistant' => $this->assistant])
            ->set('form', [
                'email' => null,
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.email' => 'required']);

    });

    test('must be a valid email', function () {

        // Act
        $lw = livewire(Assistants\Edit::class, ['assistant' => $this->assistant])
            ->set('form', [
                'email' => 'invalid-email',
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.email' => 'email']);

    });

    test('must be unique', function () {

        // Arrange
        $user = User::factory()->create();

        $assistant = Assistant::factory()->create([
            'user_id' => $user->id,
        ]);

        // Act
        $lw = livewire(Assistants\Edit::class, ['assistant' => $this->assistant])
            ->set('form', [
                'email' => $user->email,
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.email' => 'unique']);

    });

});

describe('cpf is', function () {

    test('required', function () {

        // Act
        $lw = livewire(Assistants\Edit::class, ['assistant' => $this->assistant])
            ->set('form', [
                'cpf' => null,
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.cpf' => 'required']);

    });

    test('must be a valid cpf', function () {

        // Act
        $lw = livewire(Assistants\Edit::class, ['assistant' => $this->assistant])
            ->set('form', [
                'cpf' => 'invalid-cpf',
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.cpf' => 'cpf']);

    });

    test('must be unique', function () {

        // Arrange
        $assistant = Assistant::factory()->create();

        // Act
        $lw = livewire(Assistants\Edit::class, ['assistant' => $this->assistant])
            ->set('form', [
                'cpf' => $assistant->cpf,
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.cpf' => 'unique']);

    });

});
