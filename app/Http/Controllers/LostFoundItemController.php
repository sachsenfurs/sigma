<?php

namespace App\Http\Controllers;


use App\Models\LostFoundItem;
use App\Settings\AppSettings;

class LostFoundItemController extends Controller
{
    public function index() {
        abort_unless(app(AppSettings::class)->lost_found_enabled, 404);
        $this->authorize("viewAny", LostFoundItem::class);
        return view("lostfound.index");
    }
}
