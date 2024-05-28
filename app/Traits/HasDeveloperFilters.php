<?php

namespace App\Traits;

trait HasDeveloperFilters
{
    public ?array $starOptions = null;

    public ?array $repositoriesOptions = null;

    public ?array $followersOptions = null;

    public ?array $favoriteOptions = null;

    public ?int $stars = null;

    public ?int $repositories = null;

    public ?int $followers = null;

    public ?int $favorites = null;

    private function configureDeveloperFilters(): void
    {
        $this->favorites = 0;

        $this->starOptions = $this->getSelectOptions('estrela');

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
}
