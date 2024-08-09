<?php

namespace App\Livewire\Ddas\Forms;

use App\Settings\ArtShowSettings;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ArtshowItemSignupForm extends Form
{
    #[Validate('required|min:3|max:255')]
    public $name = "";

    #[Validate('boolean')]
    public $auction = true;

    #[Validate('required_if:auction,true|int|min:0|nullable')]
    public ?int $starting_bid = 0;

    #[Validate('nullable|max:1000|string')]
    public $description = "";

    #[Validate('string|max:1000|nullable')]
    public $additional_info = "";

    #[Validate('nullable|image|max:10240')]
    public $new_image;

    public int $charity_percentage = 0;


    public function rules() {
        $charity_min = $this->auction ? app(ArtShowSettings::class)->charity_min_percentage : 0;
        return [
            'charity_percentage' => 'bail|required_if:auction,on|int|min:'.$charity_min.'|max:100',
        ];
    }

}
