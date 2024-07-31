<?php

namespace App\Livewire\Ddas\Forms;

use App\Models\Ddas\ArtshowItem;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ArtshowItemBidForm extends Form
{
    #[Validate('required|accepted|exclude')]
    public ?bool $confirm = false;

    public ?int $artshow_item_id;

    public ?int $value;

    public function rules() {
        return [
            'value' => "required|int|min:".ArtshowItem::findOrFail($this->artshow_item_id)->minBidValue(),
            'artshow_item_id' => "required|exists:".ArtshowItem::class.",id",
        ];
    }
}
