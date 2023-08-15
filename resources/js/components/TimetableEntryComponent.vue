<template>
    <div class="card mt-3">
        <div class="row g-0 flex-nowrap d-flex">
            <div class="col-lg-2 col-4 d-flex">
                <div class="card-body align-self-center text-center">
                    <h2>{{ new Date(entry.start).toLocaleTimeString("de-DE", {hour: 'numeric', minute: 'numeric'}) }}</h2>
                    <h5 class="text-muted">{{ entry.formatted_length }}</h5>
                    <h3 v-if="entry.cancelled"><span class="badge bg-danger d-block text-uppercase">Cancelled</span></h3>
                    <h3 v-else-if="entry.hasTimeChanged"><span class="badge bg-warning d-block text-uppercase">Changed</span></h3>
                </div>
            </div>
            <div class="col-lg-9 col-6 d-flex">
                <div class="card-body align-self-center">
                    <h1><a :href="link" class="text-decoration-none">{{ entry.sig_event.name_localized }}</a></h1>
                    <p class="card-text">
                        <i class="bi bi-person-circle"></i> {{ entry.sig_event.sig_host.name }}
                    </p>
                    <p>
                        <i class="bi bi-geo-alt"></i> {{ entry.sig_location.name }}
                        <span v-if="entry.hasLocationChanged" class="badge bg-danger">Changed</span>
                    </p>
                    <h3 v-for="tag in entry.sig_event.sig_tags" class="d-inline m-1">
                        <span class="badge bg-secondary">{{ tag.description_localized }}</span>
                    </h3>
                </div>
            </div>
            <div class="card-body col-lg-1 col-2 d-flex">
                <a type="button" class="fav-btn text-secondary align-self-center w-100 text-end" data-event="{{ entry.id }}">
                    <i class="bi bi-heart" style="font-size: 2em"></i>
                </a>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "TimetableEntryComponent",
    props: {
        entry: {
            id: 0,
            start: "",
            formatted_length: "",
            hasLocationChanged: false,
            hasTimeChanged: false,
            cancelled: false,
            sig_event: {
                name: "",
                name_localized: "",
                sig_host: {
                    name: "",
                },
                sig_location: {
                    name: "",
                },
                sig_tags: {
                    description_localized: "",
                }
            }
        },
    },
    mounted() {
        console.log("mounted 1");
    },
    computed: {
        link() {
            return "/show/" + this.entry.id;
        }
    }

}
</script>

<style scoped>

</style>
