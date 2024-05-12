<?php

namespace App\Livewire\Sig\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class SigHostForm extends Form
{
    #[Validate('required|string|min:3|max:26')]
    public $name;

    #[Validate('nullable|string|max:1000')]
    public $description;

}
