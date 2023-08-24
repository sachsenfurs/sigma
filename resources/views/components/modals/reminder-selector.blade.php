<div class="modal fade" id="reminderModal" tabindex="-1" role="dialog" aria-labelledby="reminderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="reminderForm" action="{{ route("") }}" method="POST">
            @method('CREATE')
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="reminderModalLabel">{{ __("Modify reminder") }}</h5>
            </div>
            <div class="modal-body">
                <p>{{ __('How many minutes before the event :event do you want to be reminded for?', [ 'event' => $event ])}}</p>
                <input type="hidden" id="timetable_entry_id" name="timetable_entry_id" value="{{ $timetableEntry }}" />
                <select class="form-select" id="minutesBefore" aria-label="Default select example">
                    <option value="15" selected>15 {{ __('Minutes') }}</option>
                    <option value="30">30 {{ __('Minutes') }}</option>
                    <option value="45">45 {{ __('Minutes') }}</option>
                    <option value="60">60 {{ __('Minutes') }}</option>
                </select>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary" data-dismiss="reminderModal">{{ __("Close") }}</a>
                <button type="submit" class="btn btn-primary">{{ __("Create") }}</button>
            </div>
        </form>
      </div>
    </div>
</div>