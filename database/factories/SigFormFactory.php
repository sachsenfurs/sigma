<?php

namespace Database\Factories;

use App\Models\SigEvent;
use App\Models\SigForm;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SigFormFactory extends Factory
{
    protected $model = SigForm::class;

    public function definition(): array
    {
        $name = ucfirst($this->faker->words(2, true));

        return [
            'slug' => Str::lower(Str::slug($name . '-' . Str::uuid())),
            'name' => $name,
            'name_en' => $name . ' EN',
            'form_definition' => [],
            'form_closed' => false,
        ];
    }

    public function withSigEvent(?SigEvent $sigEvent = null): static
    {
        return $this->afterCreating(function (SigForm $sigForm) use ($sigEvent): void {
            $sigForm->sigEvents()->attach($sigEvent?->id ?? SigEvent::factory()->create()->id);
        });
    }
}
