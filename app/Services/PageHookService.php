<?php

namespace App\Services;

use App\Models\PageHook;
use App\Settings\PageHookSettings;
use Illuminate\Support\Facades\Cache;

class PageHookService
{
    public static function resolve(string $id, string $default = ""): string {
        return Cache::remember("pagehooks.".app()->getLocale().".$id", 3600, function() use ($id, $default) {
            $content = $default;
            if($hook = PageHook::find($id)) {
                $content = app()->getLocale() == "en" ? $hook->content_en : $hook->content;
                $content = $hook->html ? strip_tags($content) : e($content);
            }

            if(app(PageHookSettings::class)->show_in_source){
                $content .= "\r\n<!-- page hook \"$id\" -->";
            }
            return $content;
        });
    }
}
