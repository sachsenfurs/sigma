<?php

namespace App\Http\Resources\Api;

use App\Models\Post\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AnnouncementsApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var $this Post
         */
        return collect($this->only([
            'text',
            'text_en',
            'created_at',
        ]))->merge([
            'image'     => $this->image ? Storage::disk("public")->url($this->image) : null,
        ])->toArray();
    }
}
