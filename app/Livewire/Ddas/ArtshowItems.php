<?php

namespace App\Livewire\Ddas;

use App\Livewire\Ddas\Forms\ArtshowItemBidForm;
use App\Livewire\Traits\HasModal;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
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

    public function render() {
        return view('livewire.ddas.artshow-items');
    }

    #[Computed]
    public function items() {
        $items = ArtshowItem::approvedItems()->orderBy("name");
        if($this->search != "")
            $items = $items->where(function($query) {
                $query->whereId($this->search)->orWhereAny(['name' ,'description', 'description_en'], 'like', "%{$this->search}%");
            });

        return $items->paginate(30);
    }

    public function showItem(ArtshowItem $item) {
        $this->form->reset();
        $this->currentItem = $item;
        $this->form->artshow_item_id = $item->id;
        $this->showModal("itemModal");
    }

    public function submitBid() {
        $this->authorize("create", [ArtshowBid::class, ArtshowItem::find($this->form->artshow_item_id)]);
        $validated = $this->form->validate();

        auth()->user()->artshowBids()->create($validated);
        $this->hideModal("itemModal");
    }
}
