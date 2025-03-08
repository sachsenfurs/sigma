<?php

namespace App\Livewire\Ddas;

use App\Livewire\Ddas\Forms\DealersForm;
use App\Livewire\Traits\HasModal;
use App\Models\Ddas\Dealer;
use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class DealersSignup extends Component
{
    use WithFileUploads, HasModal;

    public DealersForm $form;

    public function createDealer() {
        $this->authorize("create", Dealer::class);
        $validated = $this->form->validate();
        if($this->form->icon_file) {
            /**
             * @var $tempfile TemporaryUploadedFile
             */
            $tempfile = $this->form->icon_file;
            $validated['icon_file'] = basename($tempfile->store("public"));
        }
        $tags = Arr::pull($validated, "tags");
        auth()->user()->dealers()->create($validated)->tags()->sync($tags);

        $this->hideModal();
    }

    public function newDealer() {
        $this->showModal();
    }

    public function render() {
        return view('livewire.ddas.dealers-signup');
    }
}
