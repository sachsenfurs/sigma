<?php

namespace App\Livewire\Ddas\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ArtshowItemForm extends Form
{
    #[Validate('required|min:3|max:255')]
    public $name = "";

    #[Validate('boolean')]
    public $auction = true;

    #[Validate('required_if:auction,true|int|min:0|nullable')]
    public ?int $starting_bid;

    #[Validate('required_if:auction,on|int|min:0|max:100')]
    public int $charity_percentage = 0;

    #[Validate('nullable|max:1000|string')]
    public $description = "";

    #[Validate('string|max:1000|nullable')]
    public $additional_info = "";

    #[Validate('nullable|image|max:10240')]
    public $new_image;


}
