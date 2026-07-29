<?php

namespace Tests\Feature;

use App\Enums\Approval;
use App\Models\SigEvent;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\SigTag;
use App\Models\TimetableEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kidran\EventScheduleSchema\Validation\EventScheduleValidator;
use Tests\TestCase;

class BarqScheduleEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_a_schema_valid_barq_schedule(): void {
        $location = SigLocation::factory()->create([
            'name' => 'Hauptsaal',
            'name_en' => 'Main Hall',
            'description' => 'Grosser Saal',
            'description_en' => 'Large hall',
            'seats' => '120',
        ]);
        $host = SigHost::factory()->create([
            'name' => 'Kidran',
            'hide' => false,
        ]);
        $tag = SigTag::create([
            'name' => 'workshop',
            'description' => 'Workshop',
            'description_en' => 'Workshop',
        ]);
        $event = SigEvent::factory()->create([
            'name' => 'Pfoten Workshop',
            'name_en' => 'Paw Workshop',
            'description' => 'Ein Workshop.',
            'description_en' => 'A workshop.',
            'approval' => Approval::APPROVED,
            'attributes' => [
                'ticketed' => false,
                'minAge' => 0,
            ],
            'private_group_ids' => null,
        ]);

        $event->sigHosts()->attach($host);
        $event->sigTags()->attach($tag);

        TimetableEntry::factory()->create([
            'sig_event_id' => $event->id,
            'sig_location_id' => $location->id,
            'start' => now()->addDay()->setSecond(0),
            'end' => now()->addDay()->addHour()->setSecond(0),
            'hide' => false,
            'cancelled' => false,
        ]);

        $response = $this->getJson('/api/barq-schedule');

        $response->assertOk()
            ->assertJsonPath('schemaVersion', '1.0.0')
            ->assertJsonPath('sessions.0.displayName.de-DE', 'Pfoten Workshop')
            ->assertJsonPath('sessions.0.timeSlots.0.roomIds.0', 'sig-location-'.$location->id)
            ->assertJsonPath('sessions.0.timeSlots.0.hostIds.0', 'sig-host-'.$host->id)
            ->assertJsonPath('sessions.0.labelIds.0', 'sig-tag-'.$tag->id);

        $result = app(EventScheduleValidator::class)->validate($response->json(), domain: true);

        $this->assertTrue(
            $result->passes(),
            collect($result->errors())->map(fn($error) => $error->property.': '.$error->message)->implode(PHP_EOL)
        );
    }
}
