<?php

namespace App\Http\Resources\Api;

use App\Enums\Approval;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SigFilledFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        $fieldTypes = collect($this->sigForm->form_definition)
            ->pluck('type', 'data.name');

        return [
            'id' => $this->id,
            'reg_id' => $this->user->reg_id,
            'nickname' => $this->user->name,
            'accepted' => match($this->approval) {
                Approval::APPROVED => true,
                Approval::REJECTED => false,
                default => null,
            },
            'rejection_reason' => $this->rejection_reason,
            'data' => collect(data_get($this->form_data, 'form_data', []))
                ->map(function ($value, $key) use ($fieldTypes) {
                    return match ($fieldTypes->get($key)) {
                        'number' => (int) $value,
                        'file_upload' => $value ? Storage::disk('public')->url($value) : null,
                        default => $value,
                    };
                }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
