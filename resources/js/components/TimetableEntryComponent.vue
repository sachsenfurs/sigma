<template>
    <div class="card mt-3">
        <div class="row g-0 flex-nowrap d-flex">
            <div class="col-lg-2 col-4 d-flex">
                <div class="card-body align-self-center text-center">
                    <h2>{{ $entry->start->format("H:i") }}</h2>
                    <h5 class="text-muted">{{ $entry->formatted_length }}</h5>
                    @if($entry->cancelled)
                    <h3><span class="badge bg-danger d-block text-uppercase">{{ __("Cancelled") }}</span></h3>
                    @elseif($entry->hasTimeChanged())
                    <h3><span class="badge bg-warning d-block text-uppercase">{{ __("Changed") }}</span></h3>
                    @endif
                </div>
            </div>
            <div class="col-lg-9 col-6 d-flex">
                <div class="card-body align-self-center">
                    <h1><a href="{{ route("public.timeslot-show", $entry) }}" class="text-decoration-none">{{ $entry->sigEvent->name }}</a></h1>
                    <p class="card-text">
                        <i class="bi bi-person-circle"></i> {{ $entry->sigEvent->sigHost->name }}
                    </p>
                    <p>
                        <i class="bi bi-geo-alt"></i> {{ $entry->sigLocation->name }}
                        @if($entry->hasLocationChanged())
                        <span class="badge bg-danger">{{ __("Changed") }}</span>
                        @endif
                    </p>
                    @foreach($entry->sigEvent->sigTags AS $tag)
                    <h3 class="d-inline"><span class="badge bg-secondary">{{ $tag->description }}</span></h3>
                    @endforeach
                </div>
            </div>
            <div class="card-body col-lg-1 col-2 d-flex">
                <a type="button" class="fav-btn text-secondary align-self-center w-100 text-end" data-event="{{ $entry->id }}">
                    <i class="bi bi-heart" style="font-size: 2em"></i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TimetableEntryComponent"
}
</script>

<style scoped>

</style>
