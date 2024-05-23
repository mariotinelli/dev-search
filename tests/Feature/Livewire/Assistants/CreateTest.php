<?php

use App\Livewire\Assistants;
use App\Mail\SendPasswordToNewUserMail;
use App\Models\Assistant;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->loggedUser = User::factory()->create();

    $this->actingAs($this->loggedUser);
});

it('should be create a assistant', function () {

    // Arrange
    Mail::fake();

    Mail::assertNothingSent();

    $newUser = User::factory()->make();

    $newAssistant = Assistant::factory()->make([
        'created_by' => $this->loggedUser->id,
        'deleted_at' => null
    ]);

    // Act
    $lw = livewire(Assistants\Create::class)
        ->set('form', [
            'name' => $newUser->name,
            'email' => $newUser->email,
            'cpf' => $newAssistant->cpf,
        ])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertRedirect(route('assistants.index'));

    Mail::assertQueued(SendPasswordToNewUserMail::class,
        function (SendPasswordToNewUserMail $mail) use ($newUser) {
            return $mail->hasFrom(config('mail.from.address'))
                && $mail->hasTo($newUser->email);
        });

    assertDatabaseHas('users', [
        'name' => $newUser->name,
        'email' => $newUser->email,
    ]);

    assertDatabaseHas('assistants', [
        'cpf' => $newAssistant->cpf,
        'user_id' => User::whereEmail($newUser->email)->first()->id,
        'created_by' => $this->loggedUser->id,
    ]);

});

describe('name is', function () {

    test('required', function () {

        // Act
        $lw = livewire(Assistants\Create::class)
            ->set('form', [
                'name' => null,
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.name' => 'required']);

    });

    test('cannot has more than 255 characters', function () {

        // Act
        $lw = livewire(Assistants\Create::class)
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
        $lw = livewire(Assistants\Create::class)
            ->set('form', [
                'email' => null,
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.email' => 'required']);

    });

    test('must be a valid email', function () {

        // Act
        $lw = livewire(Assistants\Create::class)
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
        $lw = livewire(Assistants\Create::class)
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
        $lw = livewire(Assistants\Create::class)
            ->set('form', [
                'cpf' => null,
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.cpf' => 'required']);

    });

    test('must be a valid cpf', function () {

        // Act
        $lw = livewire(Assistants\Create::class)
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
        $lw = livewire(Assistants\Create::class)
            ->set('form', [
                'cpf' => $assistant->cpf,
            ])
            ->call('save');

        // Assert
        $lw->assertHasErrors(['form.cpf' => 'unique']);

    });

});
