<?php

namespace App\Livewire\DDAS;

use App\Livewire\DDAS\Forms\ArtshowArtistForm;
use App\Livewire\DDAS\Forms\ArtshowItemForm;
use App\Livewire\Traits\HasModal;
use App\Models\DDAS\ArtshowArtist;
use App\Models\DDAS\ArtshowItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ArtshowItemSignup extends Component
{
    use WithFileUploads, HasModal;

    public ?ArtshowItem $editArtshowItem = null;
    public ArtshowItemForm $form;
    public ArtshowArtistForm $artistForm;
    public Collection $artists;
    public ?ArtshowArtist $artistProfile = null;
    public $selectedArtistProfile;
    public $registerArtist = false;

    public function boot() {
        $this->artists = auth()->user()->artists()->get(); // fetch from database, otherwise '->artists' wouldn't update on createArtist()
        $this->artistProfile = $this->artists?->firstWhere('id', $this->selectedArtistProfile) ?? $this->artists->first();
        $this->registerArtist = $this->artists->count() == 0;
    }


    // We have to do a small workaround to directly resolve the artist via dependency injection
    public function updatedSelectedArtistProfile(ArtshowArtist $artist) {
        $this->authorize("view", $artist);
        $this->artistProfile = $artist;
    }

    public function newItem() {
        $this->form->reset();
        $this->showModal("itemModal");
    }

    public function newArtist() {
        $this->artistForm->name = auth()->user()->name;
        $this->showModal("artistModal");
    }

    public function createArtist() {
        $this->authorize("create", ArtshowArtist::class);
        $validated = $this->artistForm->validate();
        $this->artistProfile = auth()->user()->artists()->create($validated);
        $this->boot();
        $this->hideModal("artistModal");
    }

    public function editItem(ArtshowItem $artshowItem) {
        $this->authorize('update', $artshowItem);
        $this->editArtshowItem = $artshowItem;
        $this->form->reset();
        $this->form->fill($this->editArtshowItem);
        $this->showModal("itemModal");
    }

    public function deleteItem(ArtshowItem $artshowItem) {
        $this->authorize('delete', $artshowItem);
        $this->editArtshowItem = $artshowItem;
        $this->showModal("confirmModal");
    }

    public function submit() {
        $validated = $this->form->validate();

        // update image?
        unset($validated['new_image']);
        if($this->form->new_image) {
            if($this->editArtshowItem?->image AND Storage::fileExists($this->editArtshowItem->image))
                Storage::delete($this->editArtshowItem->image);

            $validated['image'] = $this->form->new_image->store('public');
        }

        if($this->editArtshowItem) {
            $this->authorize("update", $this->editArtshowItem);
            $this->editArtshowItem->update($validated);
        } else {
            $this->authorize("create", [ArtshowItem::class, $this->artistProfile]);
            $this->artistProfile->artshowItems()->create($validated);
        }
        $this->hideModal("itemModal");
    }

    public function confirm($modalId) {
        $this->authorize('delete', $this->editArtshowItem);
        $this->editArtshowItem->delete();
        $this->editArtshowItem = null;
        $this->hideModal("confirmModal");
    }
}
