<?php

namespace App\Http\Resources\Api;

use App\Models\Post\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelMarkdown\MarkdownBladeComponent;

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



        return [
            'id'            => $this->id,
            'message_id'    => $this->channels->where("language",  "!=", "en")->first()->postChannelMessage?->message_id,
            'message_id_en' => $this->channels->where("language",  "en")->first()->postChannelMessage?->message_id,
            'text'          => (new MarkdownBladeComponent())->toHtml($this->text),
            'text_en'       => (new MarkdownBladeComponent())->toHtml($this->text_en),
            'created_at'    => $this->created_at,
            'image'         => $this->image ? Storage::disk("public")->url($this->image) : null,
        ];
    }
}
