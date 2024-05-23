<?php

use App\Livewire\Assistants;
use App\Models\Assistant;
use App\Models\User;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->loggedUser = User::factory()->create();

    $this->actingAs($this->loggedUser);
});

todo('should be list all created assistants by the logged user', function () {

    // Arrange
    Assistant::factory(5)->create();

    Assistant::factory(15)->create(['created_by' => $this->loggedUser->id]);

    $assistants = Assistant::query()
        ->with(['user'])
        ->withTrashed()
        ->where('created_by', $this->loggedUser->id)
        ->latest()
        ->orderBy('deleted_at')
        ->paginate(10);

    // Act
    $lw = livewire(Assistants\Index::class)
        ->assertViewHas('assistants', function ($assistants) {
            return count($assistants) == 10;
        });;

    // Assert

//    /** @var LengthAwarePaginator $lwAssistants */
//    $lwAssistants = $lw->get('assistants');
//
//    expect($lwAssistants)->toBeInstanceOf(LengthAwarePaginator::class)
//        ->and($lwAssistants)->toBe($assistants);
//
//    $lwAssistants->each(function ($assistant, $index) use ($assistants) {
//        expect($assistant->id)->toBe($assistants[$index]->id)
//            ->and($assistant->created_by)->toBe($this->loggedUser->id);
//    });


});

it('should be disable an assistant', function () {

    // Arrange
    $assistant = Assistant::factory()
        ->create([
            'created_by' => $this->loggedUser->id,
            'deleted_at' => null
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
            'created_by' => $this->loggedUser->id,
            'deleted_at' => now()
        ]);

    // Act
    livewire(Assistants\Index::class)
        ->call('confirmedRestore', $assistant->id)
        ->assertDispatched('$refresh');

    expect($assistant->refresh()->deleted_at)->toBeNull();

});
