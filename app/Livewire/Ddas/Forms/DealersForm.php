<?php

namespace App\Livewire\Ddas\Forms;

use App\Models\Ddas\DealerTag;
use App\Settings\DealerSettings;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DealersForm extends Form
{
    #[Validate('required|min:1|max:36')]
    public string $name;

    public $icon_file;

    public $icon_file_url;

    #[Validate('string|nullable|max:1000')]
    public ?string $additional_info;

    #[Validate('url|nullable')]
    public ?string $gallery_link;

    #[Validate('string|min:10|max:1000')]
    public string $info;

    #[Validate('array|nullable|exists:'.DealerTag::class.",id")]
    public array $tags;

//    #[Validate('string|nullable|min:10|max:1000')]
//    public ?string $info_en;

    public function rules(): array {
        return [
            'icon_file' => [
                app(DealerSettings::class)->image_mandatory
                    ? 'required'
                    : 'nullable',
                'image',
                'max:10240',
            ],
        ];
    }
}
