<?php

namespace App\Livewire\Ddas;

use App\Livewire\Ddas\Forms\DealersForm;
use App\Livewire\Traits\HasModal;
use App\Models\Ddas\Dealer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Livewire\Component;
use Livewire\WithFileUploads;

class DealersSignup extends Component
{
    use WithFileUploads, HasModal;

    public DealersForm $form;

    public function createDealer() {
        $this->authorize("create", Dealer::class);
        $validated = $this->form->validate();
        if($this->form->icon_file) {
            $image = Image::read($this->form->icon_file);
            if($image->height() > 500 OR $image->width() > 500)
                $image->scaleDown(500);

            $filename = md5($image->toJpeg()->toDataUri()).".jpeg";
            if(Storage::disk('public')->put("$filename", $image->toJpeg())) {
                $validated['icon_file'] = $filename;
            }
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
