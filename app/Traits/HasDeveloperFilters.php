<?php

namespace App\Traits;

use Livewire\Attributes\Url;

trait HasDeveloperFilters
{
    #[Url(as: 's', nullable: false)]
    public ?string $search = null;

    #[Url(as: 'st', nullable: false)]
    public string $sortField = 'score';

    #[Url(as: 'dir', nullable: false)]
    public string $sortDirection = 'desc';

    #[Url(as: 'estrelas', nullable: false)]
    public ?int $stars = null;

    #[Url(as: 'repos', nullable: false)]
    public ?int $repositories = null;

    #[Url(as: 'seguidores', nullable: false)]
    public ?int $followers = null;

    #[Url(as: 'favorito', nullable: false)]
    public ?int $favorites = null;

    public ?array $starsOptions = null;

    public ?array $repositoriesOptions = null;

    public ?array $followersOptions = null;

    public ?array $favoriteOptions = null;

    private function configureDeveloperFilters(): void
    {
        $this->favorites = 0;

        $this->starsOptions = $this->getSelectOptions('estrela');

        $this->repositoriesOptions = $this->getSelectOptions('repositório');

        $this->followersOptions = $this->getSelectOptions('seguidor', 'seguidores');

        $this->favoriteOptions = [
            ['label' => 'Todos', 'value' => 0],
            ['label' => 'Favoritados', 'value' => 1],
            ['label' => 'Não favoritados', 'value' => 2],
        ];
    }

    private function getSelectOptions(string $singularName, string $pluralName = null): array
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

    public function clearFilters(): void
    {
        $this->stars = null;

        $this->repositories = null;

        $this->followers = null;

        $this->favorites = 0;
    }
}
