<div class="col-span-full w-full">
    @include("guava-calendar::widgets.calendar-widget")
    <div wire:poll.30s="refreshRecords"></div>
</div>
