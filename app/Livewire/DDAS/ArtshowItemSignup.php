<?php

namespace App\Livewire\ddas;

use App\Models\DDAS\ArtshowItem;
use Livewire\Component;

class ArtshowItemSignup extends Component
{

    public $items = [];
    public $name = "fuchx";

    public function mount() {
        $this->addItem();
    }

    public function render()
    {
        return view('livewire.ddas.artshow-item-signup');
    }

    public function addItem() {
        $item = new ArtshowItem();
        $item->pseudo_id = rand();
        $this->items[] = $item;
    }

    public function removeItem($index) {
        array_splice($this->items, $index, 1);
    }
}
