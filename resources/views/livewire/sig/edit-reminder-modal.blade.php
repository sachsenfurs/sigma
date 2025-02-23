<x-modal.livewire-modal id="editReminderModal" :title="__('Modify reminder')" action="updateReminder">
    <label for="time">
        {{ __("How many minutes before the event would you like to be notified?") }}
    </label>
    <select name="time" id="time" class="form-select my-3" wire:model="time">
        @foreach(range(15, 60, 15) AS $minutes)
            <option value="{{ $minutes }}">{{ $minutes . " " . __("Minutes") }}</option>
        @endforeach
    </select>
</x-modal.livewire-modal>
