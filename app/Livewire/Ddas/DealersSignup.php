<?php

namespace App\Livewire\Ddas;

use App\Livewire\Ddas\Forms\DealersForm;
use App\Livewire\Traits\HasModal;
use App\Models\Ddas\Dealer;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class DealersSignup extends Component
{
    use WithFileUploads, HasModal;

    public DealersForm $form;

    public int $currentDealerId = 0;

    public function createUpdateDealer(): void {
        $dealer = null;
        if($this->currentDealerId) {
            $this->authorize("update", $dealer = Dealer::find($this->currentDealerId));
        } else {
            $this->authorize("create", Dealer::class);
        }

        $validated = $this->form->validate();

        if(empty($validated['icon_file']))
            unset($validated['icon_file']);

        if($this->form->icon_file) {
            /**
             * @var $tempfile TemporaryUploadedFile
             */
            $tempfile = $this->form->icon_file;
            $validated['icon_file'] = basename($tempfile->store("public"));
        }
        $tags = Arr::pull($validated, "tags");

        if($dealer) {
            $dealer->update($validated);
            $dealer->tags()->sync($tags);
        } else {
            auth()->user()->dealers()->create($validated)->tags()->sync($tags);
        }

        $this->hideModal();
    }

    public function newDealer(): void {
        $this->currentDealerId = 0;
        $this->showModal();
    }

    public function editDealer(int $dealerId): void {
        $this->authorize("update", $dealer = Dealer::find($dealerId));
        $this->currentDealerId = $dealer->id;
        $this->form->fill($dealer);
        $this->form->icon_file_url = $dealer->icon_file_url;
        $this->form->icon_file = null;
        $this->showModal();
    }

    public function removeDealer(int $dealerId): void {
        $this->authorize("delete", $dealer = Dealer::find($dealerId));
        $this->currentDealerId = $dealer->id;
        $this->showModal("removeDealerConfirm");
    }

    public function deleteDealer(): void {
        $this->authorize("delete", $dealer = Dealer::find($this->currentDealerId));
        $dealer->delete();
        $this->currentDealerId = 0;
        $this->hideModal("removeDealerConfirm");
    }

    public function render(): View {
        return view('livewire.ddas.dealers-signup');
    }
}
