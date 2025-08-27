<div class="col-span-full w-full">
    @include("guava-calendar::widgets.calendar")
    <div wire:poll.30s="refreshRecords"></div>
</div>
