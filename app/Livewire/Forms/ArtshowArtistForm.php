<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ArtshowArtistForm extends Form
{
    #[Validate('required|string|min:3|max:120')]
    public $name;

    #[Validate('nullable|string|max:120')]
    public $social;

}
