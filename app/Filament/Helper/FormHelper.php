<?php

namespace App\Filament\Helper;

use App\Models\Ddas\DealerTag;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FormHelper
{
    public static function formatUserWithRegId(): \Closure {
        return function(?Model $record) {
            if($record instanceof User OR $record instanceof SigHost) {
                if($record->reg_id)
                    return $record->reg_id . " - " . $record->name;
                return $record->name;
            }

            return $record->name ?? $record->title ?? $record->description ?? "";
        };
    }

    public static function searchUserByNameAndRegId(): \Closure {
        return function (string $search) {
            return User::where('name', 'like', "%{$search}%")
                       ->orWhere('reg_id', $search)
                       ->limit(10)
                       ->get()
                       ->map(fn($u) => [$u->id => $u->reg_id . " - " . $u->name]) // formatting when searching for new user
                       ->toArray();
        };
    }

    public static function searchSigHostByNameAndRegId(): \Closure {
        return function (string $search) {
            return SigHost::where('name', 'like', "%{$search}%")
                       ->orWhere('reg_id', $search)
                       ->limit(10)
                       ->get()
                       ->map(fn($u) => [
                           $u->id => ($u->reg_id ? $u->reg_id  . " - " : "") . $u->name
                       ])
                       ->toArray();
        };
    }

    public static function formatLocationWithDescription(): \Closure {
        return function(?Model $record) {
            if($record instanceof SigLocation) {
                if($record->description != $record->name)
                    return $record->name . " - " . $record->description;
                return $record->name;
            }

            return $record->name ?? $record->description ?? $record->title ?? "";
        };
    }

    public static function dealerTagLocalized(): \Closure {
        return function(?Model $record) {
            if($record instanceof DealerTag) {
                return $record->name_localized;
            }

            return $record->name ?? $record->description ?? $record->title ?? "";
        };
    }
}
