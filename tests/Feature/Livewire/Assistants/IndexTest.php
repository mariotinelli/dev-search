<?php

use App\Livewire\Assistants;
use App\Models\{Assistant, User};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->loggedUser = User::factory()->create();

    $this->actingAs($this->loggedUser);
});

it('should be disable an assistant', function () {

    // Arrange
    $assistant = Assistant::factory()
        ->create([
            'deleted_at' => null,
        ]);

    // Act
    livewire(Assistants\Index::class)
        ->call('confirmedDisable', $assistant->id)
        ->assertDispatched('$refresh');

    expect($assistant->refresh()->deleted_at)->not()->toBeNull();

});

it('should be restore an assistant', function () {

    // Arrange
    $assistant = Assistant::factory()
        ->create([
            'deleted_at' => now(),
        ]);

    // Act
    livewire(Assistants\Index::class)
        ->call('confirmedRestore', $assistant->id)
        ->assertDispatched('$refresh');

    expect($assistant->refresh()->deleted_at)->toBeNull();

});
