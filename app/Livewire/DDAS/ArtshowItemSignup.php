<?php

namespace App\Livewire\DDAS;

use App\Livewire\Forms\ArtshowItemForm;
use App\Models\DDAS\ArtshowItem;
use Livewire\Component;
use Livewire\WithFileUploads;

class ArtshowItemSignup extends Component
{
    use WithFileUploads;

    public ?ArtshowItem $editArtshowItem = null;
    public ArtshowItemForm $form;

    public function newItem() {
        $this->form->reset();
        $this->dispatch("showModal", "itemModal");
    }

    public function editItem(ArtshowItem $artshowItem) {
        $this->authorize('update', $artshowItem);
        $this->editArtshowItem = $artshowItem;
        $this->form->fill($this->editArtshowItem);
        $this->dispatch("showModal", "itemModal");
    }

    public function deleteItem(ArtshowItem $artshowItem) {
        $this->authorize('delete', $artshowItem);
        $this->editArtshowItem = $artshowItem;
        $this->dispatch("showModal", "confirmModal");
    }

    public function submit() {
        $this->form->store($this->editArtshowItem);
        $this->dispatch("hideModal", "itemModal");
    }

    public function confirm($modalId) {
        $this->authorize('delete', $this->editArtshowItem);
        $this->editArtshowItem->delete();
        $this->dispatch("hideModal", "confirmModal");
    }
}
