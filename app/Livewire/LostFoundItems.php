<?php

namespace App\Livewire;

use App\Models\LostFoundItem;
use App\Settings\AppSettings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class LostFoundItems extends Component
{
    use WithPagination;

    protected int $perPage = 30;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'tab')]
    public string $tab = 'lost';

    public function mount(): void {
        abort_unless(app(AppSettings::class)->lost_found_enabled, 404);
        Gate::authorize('viewAny', LostFoundItem::class);

        if (! in_array($this->tab, ['lost', 'found'], true)) {
            $this->tab = 'lost';
        }
    }

    public function render(): View {
        return view('livewire.lost-found-items');
    }

    public function setTab(string $tab): void {
        if (in_array($tab, ['lost', 'found'], true)) {
            $this->tab = $tab;
            $this->resetPage();
        }
    }

    #[Computed]
    public function lostCount(): int {
        return $this->queryItems('lost')->count();
    }

    #[Computed]
    public function foundCount(): int {
        return $this->queryItems('found')->count();
    }

    #[Computed]
    public function itemsPaginated() {
        return $this->queryItems($this->tab)->paginate($this->perPage);
    }

    public function updatedSearch(): void {
        $this->resetPage();
    }

    protected function queryItems(string $type): Builder {
        $search = trim($this->search);
        $query = $type === 'lost' ? LostFoundItem::lost() : LostFoundItem::found();

        if ($search !== '') {
            $query->where(function (Builder $query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}
