<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\EssentialLocationsApiResource;
use App\Http\Resources\Api\EventApiResource;
use App\Http\Resources\Api\LocationApiResource;
use App\Http\Resources\Api\SocialApiResource;
use App\Models\Info\Social;
use App\Models\SigLocation;
use App\Models\TimetableEntry;
use Illuminate\Http\Resources\Json\JsonResource;

class SignageEndpointController extends Controller
{
    public function __construct() {
        JsonResource::withoutWrapping();
    }

    public function events() {
        // allow for API (signed route) otherwise only if schedule is public
        if(!request()->hasValidSignature()) {
            $this->authorize("viewAny", TimetableEntry::class);
            $entries = TimetableEntry::public();
        } else {
            $entries = TimetableEntry::query();
        }

        return EventApiResource::collection(
            $entries->orderBy("start", "ASC")->get()
        );
    }

    public function essentials() {
        return EssentialLocationsApiResource::collection(
            SigLocation::where("essential", true)->get()
        );
    }

    public function locations() {
        return LocationApiResource::collection(
            SigLocation::all()
        );
    }

    public function socials() {
        return SocialApiResource::collection(
            Social::signage()->get()
        );
    }
}
