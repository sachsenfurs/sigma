<?php

use App\Enums\Approval;
use App\Enums\MessageRecipient;
use App\Enums\Permission;
use App\Enums\PermissionLevel;
use App\Filament\Pages\SendMassMail;
use App\Models\Ddas\ArtshowArtist;
use App\Models\Ddas\ArtshowBid;
use App\Models\Ddas\ArtshowItem;
use App\Models\Ddas\Dealer;
use App\Models\SigAttendee;
use App\Models\SigEvent;
use App\Models\SigFavorite;
use App\Models\SigFilledForm;
use App\Models\SigForm;
use App\Models\SigHost;
use App\Models\SigLocation;
use App\Models\SigTimeslot;
use App\Models\TimetableEntry;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserRolePermission;
use App\Notifications\Messages\MessageNotification;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses(RefreshDatabase::class);

dataset('message recipients', array_combine(
    array_map(static fn (MessageRecipient $case): string => $case->name, MessageRecipient::cases()),
    array_map(static fn (MessageRecipient $case): array => [$case], MessageRecipient::cases()),
));

it('sends a mass mail to the expected recipients for each message recipient type', function (MessageRecipient $recipientType) {
    $admin = createMassMailAdmin();
    [$formData, $expectedRecipients, $unexpectedRecipients] = createScenario($recipientType);

    Notification::fake();

    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs($admin);

    $component = Livewire::test(SendMassMail::class)
        ->set('data.subject', 'Mass mail subject')
        ->set('data.body', "First line\nSecond line")
        ->set('data.recipients', $recipientType->name);

    if (array_key_exists('model', $formData)) {
        $component->set('data.model', $formData['model']);
    }

    $component->call('submit');

    Notification::assertCount(count($expectedRecipients));

    foreach ($expectedRecipients as $recipient) {
        Notification::assertSentToTimes($recipient, MessageNotification::class, 1);
    }

    foreach ($unexpectedRecipients as $recipient) {
        Notification::assertNotSentTo($recipient, MessageNotification::class);
    }
})->with('message recipients');

function createMassMailAdmin(): User
{
    return Model::withoutEvents(function (): User {
        $admin = User::factory()->create();
        $role = UserRole::create([
            'name' => 'Mass Mail Admin',
            'name_en' => 'Mass Mail Admin',
        ]);

        UserRolePermission::create([
            'user_role_id' => $role->id,
            'permission' => Permission::MANAGE_CHATS,
            'level' => PermissionLevel::DELETE,
        ]);

        $admin->roles()->attach($role);

        return $admin->fresh();
    });
}

function createScenario(MessageRecipient $recipientType): array
{
    return Model::withoutEvents(fn (): array => match ($recipientType) {
        MessageRecipient::USER_ROLE => createUserRoleScenario(),
        MessageRecipient::HOST => createHostScenario(),
        MessageRecipient::FILLED_FORM_USER => createFilledFormScenario(Approval::PENDING, Approval::APPROVED),
        MessageRecipient::FILLED_FORM_USER_APPROVED => createFilledFormScenario(Approval::APPROVED, Approval::REJECTED),
        MessageRecipient::FILLED_FORM_USER_REJECTED => createFilledFormScenario(Approval::REJECTED, Approval::APPROVED),
        MessageRecipient::DEALER => createAllDealerScenario(),
        MessageRecipient::DEALER_APPROVED => createDealerScenario(Approval::APPROVED, Approval::REJECTED),
        MessageRecipient::DEALER_REJECTED => createDealerScenario(Approval::REJECTED, Approval::APPROVED),
        MessageRecipient::ARTIST => createArtistScenario(),
        MessageRecipient::ARTSHOW_WINNER => createArtshowWinnerScenario(),
        MessageRecipient::TIMETABLE_ENTRY_ATTENDEE => createTimetableEntryAttendeeScenario(),
        MessageRecipient::TIMETABLE_ENTRY_FAVED => createTimetableEntryFavoriteScenario(),
    });
}

function createUserRoleScenario(): array
{
    $role = UserRole::create([
        'name' => 'Target Department',
        'name_en' => 'Target Department',
    ]);

    $expected = User::factory()->create();
    $unexpected = User::factory()->create();

    $role->users()->attach($expected);

    return [
        ['model' => $role->id],
        [$expected],
        [$unexpected],
    ];
}

function createHostScenario(): array
{
    $expected = User::factory()->create();
    $unexpected = User::factory()->create();
    $host = SigHost::factory()->create([
        'reg_id' => $expected->reg_id,
    ]);
    $event = SigEvent::factory()->create([
        'private_group_ids' => null,
    ]);

    $host->sigEvents()->attach($event);

    return [
        [],
        [$expected],
        [$unexpected],
    ];
}

function createFilledFormScenario(Approval $matchingApproval, Approval $nonMatchingApproval): array
{
    $form = createSigForm('target-form');
    $otherForm = createSigForm('other-form');
    $expected = User::factory()->create();
    $unexpected = User::factory()->create();

    SigFilledForm::create([
        'sig_form_id' => $form->id,
        'user_id' => $expected->id,
        'approval' => $matchingApproval,
        'form_data' => [],
    ]);

    SigFilledForm::create([
        'sig_form_id' => $matchingApproval === Approval::PENDING ? $otherForm->id : $form->id,
        'user_id' => $unexpected->id,
        'approval' => $nonMatchingApproval,
        'form_data' => [],
    ]);

    return [
        ['model' => $form->id],
        [$expected],
        [$unexpected],
    ];
}

function createDealerScenario(Approval $matchingApproval, Approval $nonMatchingApproval): array
{
    $location = SigLocation::factory()->create();
    $expected = User::factory()->create();
    $unexpected = User::factory()->create();

    Dealer::factory()->create([
        'user_id' => $expected->id,
        'approval' => $matchingApproval->value,
        'sig_location_id' => $location->id,
        'icon_file' => null,
    ]);

    Dealer::factory()->create([
        'user_id' => $unexpected->id,
        'approval' => $nonMatchingApproval->value,
        'sig_location_id' => $location->id,
        'icon_file' => null,
    ]);

    return [
        [],
        [$expected],
        [$unexpected],
    ];
}

function createAllDealerScenario(): array
{
    $location = SigLocation::factory()->create();
    $expected = User::factory()->create();
    $unexpected = User::factory()->create();

    Dealer::factory()->create([
        'user_id' => $expected->id,
        'approval' => Approval::PENDING->value,
        'sig_location_id' => $location->id,
        'icon_file' => null,
    ]);

    return [
        [],
        [$expected],
        [$unexpected],
    ];
}

function createArtistScenario(): array
{
    $expected = User::factory()->create();
    $unexpected = User::factory()->create();

    ArtshowArtist::factory()->create([
        'user_id' => $expected->id,
    ]);

    return [
        [],
        [$expected],
        [$unexpected],
    ];
}

function createArtshowWinnerScenario(): array
{
    $artist = ArtshowArtist::factory()->create();
    $expected = User::factory()->create();
    $unexpected = User::factory()->create();
    $item = ArtshowItem::factory()->create([
        'artshow_artist_id' => $artist->id,
        'approval' => Approval::APPROVED->value,
        'image' => null,
    ]);

    ArtshowBid::create([
        'artshow_item_id' => $item->id,
        'user_id' => $unexpected->id,
        'value' => 10,
    ]);

    ArtshowBid::create([
        'artshow_item_id' => $item->id,
        'user_id' => $expected->id,
        'value' => 20,
    ]);

    return [
        [],
        [$expected],
        [$unexpected],
    ];
}

function createTimetableEntryAttendeeScenario(): array
{
    $entry = createTimetableEntry();
    $otherEntry = createTimetableEntry();
    $expected = User::factory()->create();
    $unexpected = User::factory()->create();
    $timeslot = SigTimeslot::create([
        'timetable_entry_id' => $entry->id,
        'max_users' => 5,
        'slot_start' => now()->addDay(),
        'slot_end' => now()->addDay()->addHour(),
        'reg_start' => now()->subDay(),
        'reg_end' => now()->addDay(),
        'self_register' => true,
    ]);
    $otherTimeslot = SigTimeslot::create([
        'timetable_entry_id' => $otherEntry->id,
        'max_users' => 5,
        'slot_start' => now()->addDays(2),
        'slot_end' => now()->addDays(2)->addHour(),
        'reg_start' => now()->subDay(),
        'reg_end' => now()->addDay(),
        'self_register' => true,
    ]);

    SigAttendee::create([
        'user_id' => $expected->id,
        'sig_timeslot_id' => $timeslot->id,
    ]);

    SigAttendee::create([
        'user_id' => $unexpected->id,
        'sig_timeslot_id' => $otherTimeslot->id,
    ]);

    return [
        ['model' => $entry->id],
        [$expected],
        [$unexpected],
    ];
}

function createTimetableEntryFavoriteScenario(): array
{
    $entry = createTimetableEntry();
    $otherEntry = createTimetableEntry();
    $expected = User::factory()->create();
    $unexpected = User::factory()->create();

    SigFavorite::create([
        'user_id' => $expected->id,
        'timetable_entry_id' => $entry->id,
    ]);

    SigFavorite::create([
        'user_id' => $unexpected->id,
        'timetable_entry_id' => $otherEntry->id,
    ]);

    return [
        ['model' => $entry->id],
        [$expected],
        [$unexpected],
    ];
}

function createSigForm(string $prefix): SigForm
{
    return SigForm::create([
        'slug' => $prefix . '-' . Str::lower((string) Str::uuid()),
        'name' => Str::headline($prefix),
        'name_en' => Str::headline($prefix),
        'form_definition' => [],
    ]);
}

function createTimetableEntry(): TimetableEntry
{
    $event = SigEvent::factory()->create([
        'private_group_ids' => null,
    ]);
    $location = SigLocation::factory()->create();

    return TimetableEntry::factory()->create([
        'sig_event_id' => $event->id,
        'sig_location_id' => $location->id,
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHour(),
        'hide' => false,
        'cancelled' => false,
        'new' => false,
    ]);
}
