@props([
    'sigTimeslot',
])
<div class="modal fade" id="TimeslotReminderModal{{ $sigTimeslot->id }}" tabindex="-1" role="dialog" aria-labelledby="TimeslotReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="TimeslotReminderForm{{ $sigTimeslot->id }}" action="" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="TimeslotReminderModalLabel">{{ __("Modify reminder") }}</h5>
            </div>
            <div class="modal-body">
                <p>{{ __('How many minutes before the event :event do you want to be reminded for?', [ 'event' => $sigTimeslot->timetableEntry->sigEvent->name ])}}</p>
                <input type="hidden" id="timeslot_id" name="timeslot_id" value="{{ $sigTimeslot->id }}" />
                <select class="form-select" name="minutes_before" id="minutes_before" aria-label="Select minutes">
                    <option value="15" selected>15 {{ __('Minutes') }}</option>
                    <option value="30">30 {{ __('Minutes') }}</option>
                    <option value="45">45 {{ __('Minutes') }}</option>
                    <option value="60">60 {{ __('Minutes') }}</option>
                </select>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" onclick="$('#TimeslotReminderModal{{ $sigTimeslot->id }}').modal('toggle')" data-dismiss="reminderModal">{{ __("Close") }}</a>
                <button type="submit" class="btn btn-primary">{{ __("Update") }}</button>
            </div>
        </form>
      </div>
    </div>
</div>