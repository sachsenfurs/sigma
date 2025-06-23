<?php

namespace App\Livewire\User\Forms;

use App\Facades\NotificationService;
use Illuminate\Support\Collection;
use Livewire\Form;

class SettingsNotificationForm extends Form
{
    public Collection $types;

    public function save(): void {
        $this->validate();

        auth()->user()->update([
            'notification_channels' => $this->types->map(function($type) {
                return array_keys(collect($type)->filter(fn($c) => $c)->toArray());
            })
        ]);

    }

    public function rules() {
        return [
            'types' => [
                'array:'.implode(",", array_values(NotificationService::userNotifications())),
            ]
        ];
    }

}
