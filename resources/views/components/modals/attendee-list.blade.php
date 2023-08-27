@props([
    'timeslot',
])
<div class="modal fade" id="attendeeListModal{{ $timeslot->id }}" tabindex="-1" role="dialog" aria-labelledby="attendeeListModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="attendeeListModalLabel">{{ __("Attendee List") }}</h5>
        </div>
        <div class="modal-body">
                @foreach ($timeslot->sigAttendees as $attendee)
                    <x-list-attendee-item
                        :name="$attendee->user->name"
                        :avatar="$attendee->user->avatar"
                    />
                @endforeach 
        </div>
        <div class="modal-footer">
            <a class="btn btn-secondary" onclick="$('#attendeeListModal{{ $timeslot->id }}').modal('toggle');" data-dismiss="modal">{{ __("Close") }}</a>
        </div>
      </div>
    </div>
</div>