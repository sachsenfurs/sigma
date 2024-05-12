<?php

namespace App\Livewire\Sig;

use App\Livewire\Sig\Forms\SigHostForm;
use App\Livewire\Traits\HasModal;
use App\Models\SigHost;
use Livewire\Component;

class Create extends Component
{
    use HasModal;

    public SigHostForm $form;


    public function createHost() {
        $this->form->reset();
        $this->form->name = auth()->user()->name;
        $this->showModal("hostModal");
    }

    public function storeHost() {
        $this->authorize("create", [SigHost::class, auth()->user()->reg_id]);

        $validated = $this->form->validate();

        auth()->user()->sigHosts()->create($validated);

        $this->hideModal("hostModal");
        $this->redirect(route("sigs.create"));
    }

}
