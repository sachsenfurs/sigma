<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\LassieEventsExportResource;
use App\Models\TimetableEntry;
use App\Settings\AppSettings;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class LassieExportEndpoint extends Controller
{
    public function __construct() {
        JsonResource::withoutWrapping();
    }

    public function __invoke() {
        Gate::denyIf(!request()->hasValidSignature() AND request('api_key') != app(AppSettings::class)->lassie_api_key);

        return LassieEventsExportResource::collection(
            TimetableEntry::public()->with(["sigEvent", "sigLocation"])->orderBy("start")->get()
        );
    }
}
