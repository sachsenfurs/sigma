<?php

namespace App\Livewire\Ddas;

use App\Events\Ddas\ArtshowItemSubmitted;
use App\Livewire\Ddas\Forms\ArtshowArtistForm;
use App\Livewire\Ddas\Forms\ArtshowItemSignupForm;
use App\Livewire\Traits\HasModal;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class ArtshowItemSignup extends Component
{
    use WithFileUploads, HasModal;

    public ?ArtshowItem $editArtshowItem = null;
    public ArtshowItemSignupForm $form;
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
        $this->editArtshowItem = null;
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
        // set default
        if(!$this->form->starting_bid)
            $this->form->starting_bid = 0;

        $validated = $this->form->validate();

        // update image?
        unset($validated['new_image']);
        if($this->form->new_image) {
            if($this->editArtshowItem?->image AND Storage::fileExists($this->editArtshowItem->image))
                Storage::delete($this->editArtshowItem->image);

            $image = Image::read($this->form->new_image);
            if($image->height() > 500 OR $image->width() > 500)
                $image->scaleDown(500);

            $filename = md5($image->toJpeg()->toDataUri()).".jpeg";
            if(Storage::disk('public')->put("$filename", $image->toJpeg())) {
                $validated['image'] = $filename;
            }
        }

        if($this->editArtshowItem) {
            $this->authorize("update", $this->editArtshowItem);
            $this->editArtshowItem->update($validated);
        } else {
            $this->authorize("create", [ArtshowItem::class, $this->artistProfile]);
            $item = $this->artistProfile->artshowItems()->create($validated);
            ArtshowItemSubmitted::dispatch($item);
        }
        $this->hideModal("itemModal");
    }

    public function confirm($modalId) {
        $this->authorize('delete', $this->editArtshowItem);
        $this->editArtshowItem?->delete();
        $this->editArtshowItem = null;
        $this->hideModal("confirmModal");
    }
}
