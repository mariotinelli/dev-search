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
