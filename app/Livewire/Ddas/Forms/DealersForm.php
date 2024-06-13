<?php

namespace App\Livewire\Ddas\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class DealersForm extends Form
{
    #[Validate('required|min:1|max:36')]
    public string $name;

    #[Validate('nullable|image|max:10240')]
    public $icon_file;

    #[Validate('string|nullable|max:1000')]
    public ?string $additional_info;

    #[Validate('url|nullable')]
    public ?string $gallery_link;

    #[Validate('string|min:10|max:1000')]
    public string $info;

//    #[Validate('string|nullable|min:10|max:1000')]
//    public ?string $info_en;
}
