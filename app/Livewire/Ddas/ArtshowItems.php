<?php

namespace App\Livewire\Ddas;

use App\Livewire\Ddas\Forms\ArtshowItemBidForm;
use App\Livewire\Traits\HasModal;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ArtshowItems extends Component
{
    use WithPagination, HasModal;

    public ArtshowItem|null $currentItem = null;

    public ArtshowItemBidForm $form;

    #[Url]
    public string $search = "";

    #[Url]
    public int $artist = 0;

    public bool $showOnlyMyBids = false;

    public function render(): View {
        return view('livewire.ddas.artshow-items');
    }

    public function getItems($filterArtist=true): Builder {
        $items = ArtshowItem::approvedItems()->orderBy("name");

        if($this->showOnlyMyBids)
            $items = $items->whereHas("artshowBids", function(Builder $query) {
                return $query->where("user_id", auth()->id());
            });

        if($this->search != "")
            $items = $items->where(function(Builder $query) {
                if(ctype_digit($this->search))
                    $query->whereId($this->search);
                else
                    $query->orWhereAny(['name' ,'description', 'description_en'], 'like', "%{$this->search}%");
            });
        if($this->artist AND $filterArtist)
            $items->where("artshow_artist_id", $this->artist);
        return $items;
    }

    #[Computed]
    public function itemsPaginated() {
        return $this->getItems()->paginate(30);
    }

    #[Computed]
    public function items() {
        return $this->getItems()->get();
    }

    #[Computed] // separate computed property so livewire can do caching and stuff
    public function itemsWithoutArtistFilter() {
        return $this->getItems(false)->get();
    }

    #[Computed]
    public function artists() {
        return ArtshowArtist::havingApprovedItems()->get()->sortBy("name");
    }

    public function showItem(ArtshowItem $item): void {
        $this->form->reset();
        $this->currentItem = $item;
        $this->form->artshow_item_id = $item->id;
        $this->showModal("itemModal");
    }

    public function submitBid(): void {
        $this->authorize("create", [ArtshowBid::class, ArtshowItem::find($this->form->artshow_item_id)]);
        $validated = $this->form->validate();

        auth()->user()->artshowBids()->create($validated);
        $this->hideModal("itemModal");
    }
}
