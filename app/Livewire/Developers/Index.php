<?php

namespace App\Livewire\Developers;

use App\Models\Developer;
use App\Traits\HasDeveloperFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\{LengthAwarePaginator};
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class Index extends Component
{
    use Interactions;
    use HasDeveloperFilters;

    public function render(): View
    {
        return view('livewire.developers.index')->layout('layouts.app');
    }

    public function mount(): void
    {
        $this->configureDeveloperFilters();
    }

    #[Computed]
    public function developers(): LengthAwarePaginator
    {
        $developers = Developer::query()
            ->with(['favoriteBy'])
            ->when($this->stars, function (Builder $query, int $stars) {
                return $query->where('stars', '>', $stars);
            })
            ->when($this->repositories, function (Builder $query, int $repositories) {
                return $query->where('repos', '>', $repositories);
            })
            ->when($this->followers, function (Builder $query, int $followers) {
                return $query->where('followers', '>', $followers);
            })
            ->when($this->favorites, function (Builder $query, $favorites) {
                if ($favorites === 0) {
                    return $query;
                }

                $relationQuery = $favorites == 1 ? "whereHas" : "whereDoesntHave";

                return $query->{$relationQuery}('favoriteBy', function (Builder $query) {
                    return $query->where('user_id', auth()->id());
                });
            })
            ->orderByDesc('score')
            ->paginate();

        return tap($developers, function (LengthAwarePaginator $developers) {
            $developers->getCollection()->transform(function (Developer $developer) {
                $developer->is_favorite = $developer->favoriteBy->contains('user_id', auth()->id());

                return $developer;
            });
        });
    }

    public function favoriteDeveloper(int $id): void
    {
        auth()->user()->favoriteDevelopers()->create(['developer_id' => $id]);

        $this->toast()
            ->success('Desenvolvedor favoritado com sucesso')
            ->send();
    }

    public function unfavoriteDeveloper(int $id): void
    {
        auth()->user()->favoriteDevelopers()->where('developer_id', $id)->delete();

        $this->toast()
            ->success('Desenvolvedor removido dos favoritos com sucesso')
            ->send();
    }
}
