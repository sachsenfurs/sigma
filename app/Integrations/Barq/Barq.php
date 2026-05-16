<?php

namespace App\Integrations\Barq;

use App\Integrations\Barq\Operations\CreateEventAnnouncement;
use App\Settings\AppSettings;

class Barq
{

    /**
     * use methods with laravel service container app(Barq::class)->createEventAnnouncement()..
     */


    /**
     * @param string $title
     * @param string $body
     * @return string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createEventAnnouncement(string $title, string $body): string {
        return app()->makeWith(CreateEventAnnouncement::class, [
            'uuid' => app(AppSettings::class)->barq_event_uuid,
            'title' => $title,
            'body' => $body,
        ])->execute();
    }
}
