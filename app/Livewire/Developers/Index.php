<?php

namespace App\Livewire\Developers;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public ?array $starOptions = null;

    public ?array $repositoriesOptions = null;

    public ?array $followersOptions = null;

    public ?array $languageOptions = null;

    public ?array $languages = null;

    public ?int $stars = null;

    public ?int $repositories = null;

    public ?int $followers = null;

    public function render(): View
    {
        return view('livewire.developers.index')
            ->layout('layouts.app');
    }

    public function mount(): void
    {
        $this->starOptions = $this->getSelectOptions('estrela');

        $this->repositoriesOptions = $this->getSelectOptions('repositório');

        $this->followersOptions = $this->getSelectOptions('seguidor', 'seguidores');
    }

//    public function favoriteDeveloper(int $id): void
//    {
//        auth()->user()->favoriteDevelopers()->toggle($id);
//    }

    #[Computed]
    public function developers(): LengthAwarePaginator
    {
        return Developer::query()
            ->when($this->stars, function (Builder $query, int $stars) {
                return $query->where('stars', '>', $stars);
            })
            ->when($this->repositories, function (Builder $query, int $repositories) {
                return $query->where('repos', '>', $repositories);
            })
            ->when($this->followers, function (Builder $query, int $followers) {
                return $query->where('followers', '>', $followers);
            })
            ->orderBy('score', 'desc')
            ->paginate(10);
    }

    public function getSelectOptions(string $singularName, string $pluralName = null): array
    {
        $pluralName = $pluralName ?? $singularName . 's';

        return [
            ['label' => "Mais de 1 {$singularName}", 'value' => 1],
            ['label' => "Mais de 10 {$pluralName}", 'value' => 10],
            ['label' => "Mais de 25 {$pluralName}", 'value' => 25],
            ['label' => "Mais de 50 {$pluralName}", 'value' => 50],
            ['label' => "Mais de 100 {$pluralName}", 'value' => 100],
            ['label' => "Mais de 500 {$pluralName}", 'value' => 500],
            ['label' => "Mais de 1000 {$pluralName}", 'value' => 1000],
        ];
    }
}
