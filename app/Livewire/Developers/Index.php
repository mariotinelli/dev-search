<?php

namespace App\Livewire\Developers;

use App\Integrations\Github\GithubIntegration;
use Illuminate\Pagination\{LengthAwarePaginator, Paginator};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
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

    public ?string $stars = null;

    public ?string $repositories = null;

    public ?string $followers = null;

    public function render(): View
    {
        return view('livewire.developers.index')
            ->layout('layouts.app');
    }

    public function mount(): void
    {
        $this->languageOptions = ['php', 'javascript', 'python', 'ruby', 'java', 'c#', 'c++', 'c', 'go', 'typescript', 'shell', 'swift', 'kotlin', 'rust', 'scala', 'r', 'css', 'html', 'vue', 'react', 'angular', 'laravel', 'symfony', 'django', 'flask', 'express', 'spring', 'rails', 'asp.net', 'asp.net core', 'node.js', 'deno', 'next.js', 'nuxt.js', 'gatsby', 'react native', 'flutter', 'ionic', 'cordova', 'xamarin', 'electron', 'nw.js', 'unity', 'unreal', 'godot', 'phaser', 'p5.js', 'three.js', 'babylon.js', 'a-frame', 'jquery', 'bootstrap', 'tailwind', 'bulma', 'materialize', 'semantic ui', 'chakra ui', 'ant design', 'vuetify', 'quasar', 'element', 'primevue', 'primefaces', 'material-ui', 'mui', 'antd', 'tailwindcss', 'bulma', 'bootstrap', 'materialize', 'semantic ui', 'chakra ui', 'ant design', 'vuetify', 'quasar', 'element', 'primevue', 'primefaces', 'material-ui', 'mui', 'antd', 'tailwindcss', 'bulma', 'bootstrap', 'materialize', 'semantic ui', 'chakra ui', 'ant design', 'vuetify', 'quasar', 'element', 'primevue', 'primefaces', 'material-ui', 'mui', 'antd', 'tailwindcss', 'bulma', 'bootstrap', 'materialize', 'semantic ui', 'chakra ui', 'ant design', 'vuetify', 'quasar', 'element', 'primevue', 'primefaces', 'material-ui', 'mui', 'antd', 'tailwindcss', 'bulma', 'bootstrap', 'materialize', 'semantic ui', 'chakra ui', 'ant design', 'vuetify', 'quasar', 'element', 'primevue', 'primefaces', 'material-ui', 'mui', 'antd', 'tailwindcss', 'bulma', 'bootstrap', 'materialize', 'semantic ui', 'chakra ui', 'ant'];

        $this->starOptions = $this->getSelectOptions('estrela');

        $this->repositoriesOptions = $this->getSelectOptions('repositório');

        $this->followersOptions = $this->getSelectOptions('seguidor', 'seguidores');
    }

    #[Computed]
    public function developers(): LengthAwarePaginator
    {
        //        $users = collect();
        //
        //        for ($i = 0; $i < 15; $i++) {
        //            $users->push(new User(
        //                login: 'josepholiveira',
        //                avatarUrl: 'https://avatars.githubusercontent.com/u/3952934?v=4',
        //                htmlUrl: 'https://avatars.githubusercontent.com/u/3952934?v=4',
        //                name: 'Joseph Oliveira',
        //                email: 'email@email.com',
        //                followers: 100,
        //                stars: 25
        //            ));
        //        }
        //
        //        return $this->paginate($users);

        if (!$this->stars || !$this->repositories || !$this->followers || !$this->languages) {
            return $this->paginate(collect());
        }

        $expression = "followers:{$this->followers}+repos:{$this->repositories}";

        foreach ($this->languages as $language) {
            $expression .= "+language:{$language}";
        }

        $users = (new GithubIntegration())->searchUsers($expression);

        return $this->paginate($users);
    }

    public function paginate(Collection $users, int $perPage = null, int $page = null): LengthAwarePaginator
    {
        $page = $page ?? (Paginator::resolveCurrentPage() ?: 1);

        $items = Collection::make($users);

        return new LengthAwarePaginator(
            items: $items->forPage($page, $perPage ?? 10),
            total: $items->count(),
            perPage: $perPage ?? 10,
            currentPage: $page,
            options: [
                'path'  => Request::url(),
                'query' => Request::query(),
            ]
        );
    }

    public function getSelectOptions(string $singularName, string $pluralName = null): array
    {
        $pluralName = $pluralName ?? $singularName . 's';

        return [
            [
                'label' => "Mais de 1 {$singularName}",
                'value' => '>1',
            ],
            [
                'label' => "Mais de 10 {$pluralName}",
                'value' => '>10',
            ],
            [
                'label' => "Mais de 25 {$pluralName}",
                'value' => '>25',
            ],
            [
                'label' => "Mais de 50 {$pluralName}",
                'value' => '>50',
            ],
            [
                'label' => "Mais de 100 {$pluralName}",
                'value' => '>100',
            ],
            [
                'label' => "Mais de 500 {$pluralName}",
                'value' => '>500',
            ],
            [
                'label' => "Mais de 1000 {$pluralName}",
                'value' => '>1000',
            ],
        ];
    }
}
