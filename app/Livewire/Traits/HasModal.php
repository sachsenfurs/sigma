<?php

namespace App\Livewire\Traits;

trait HasModal
{
    public function showModal($modalName="modal") {
        $this->dispatch("showModal", $modalName);
    }

    public function hideModal($modalName="modal") {
        $this->dispatch("hideModal", $modalName);
    }
}
