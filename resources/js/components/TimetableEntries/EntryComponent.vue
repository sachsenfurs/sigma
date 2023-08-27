<template>
    <div :class="['card mt-3', { 'opacity-50': eventPassed }]">
        <div class="row g-0 flex-nowrap d-flex">
            <div class="col-lg-2 col-4 d-flex">
                <div class="card-body align-self-center text-center">
                    <h2>
                        <i v-if="eventRunning" class="bi bi-record-fill text-danger blink"></i>
                        {{
                            new Date(entry.start).toLocaleTimeString("de-DE", {
                                hour: "numeric",
                                minute: "numeric",
                            })
                        }}
                    </h2>
                    <h5 class="text-muted">{{ entry.formatted_length }}</h5>
                    <h3 v-if="entry.cancelled">
                        <span class="badge bg-danger d-block text-uppercase">Cancelled</span>
                    </h3>
                    <h3 v-else-if="entry.hasTimeChanged">
                        <span class="badge bg-warning d-block text-uppercase">Changed</span>
                    </h3>
                    <div v-if="entry.sig_event.languages.length > 0" class="mt-3">
                        <img v-for="lang in entry.sig_event.languages" :src="'/icons/' + lang + '-flag.svg'" class="m-1" style="height: 1.2em; opacity: 0.7" :alt="'[' + lang.toUpperCase() + ']'" />
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-7 d-flex">
                <div class="card-body align-self-center pe-0">
                    <h1>
                        <a :href="link" class="text-decoration-none" :id="'event' + entry.id">{{ entry.sig_event.name_localized }}</a>
                    </h1>

                    <p v-if="!entry.sig_event.sig_host.hide" class="card-text">
                        <i class="bi bi-person-circle"></i>
                        {{ entry.sig_event.sig_host.name }}
                    </p>
                    <p>
                        <i class="bi bi-geo-alt"></i>
                        {{ entry.sig_location.name }}
                        <span v-if="entry.hasLocationChanged" class="badge bg-danger">Changed</span>
                    </p>

                    <h3
                        v-for="tag in entry.sig_event.sig_tags"
                        class="d-inline m-1"
                    >
                        <span class="badge bg-secondary">{{ tag.description_localized }}</span>
                    </h3>
                </div>
            </div>
            <div class="card-body col-lg-1 col-1 d-flex ps-0">
                <a type="button"
                    class="fav-btn text-secondary align-self-center w-100 text-end"
                    data-event="{{ entry.id }}"
                >
                    <i class="bi bi-heart" style="font-size: 2em"></i>
                </a>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "EntryComponent",
    props: {
        id :"",
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
                },
            },
        },
    },
    mounted() {
        let self = this;
        setInterval(function() {
            self.now = new Date();
        }, 10000);

        // blink effect (separate function to SYNC them!)
        function blinkFadeInOut(dirBool) {
            document.querySelectorAll(".blink").forEach((el) => el.style.opacity = (dirBool ? 1 : 0));
            setTimeout(() => blinkFadeInOut(!dirBool), 1050);
        }
        blinkFadeInOut(true);
    },
    computed: {
        link() {
            return "/show/" + this.entry.id;
        },
        eventRunning() {
            return !this.entry.cancelled && this.now >= new Date(this.entry.start) && !this.eventPassed;
        },
        eventPassed() {
            return this.now >= new Date(this.entry.end);
        }
    },
    data() {
        return {
            now: Date.now()
        }
    }
};
</script>

<style scoped>

.blink {
    transition: opacity 1s ease-in-out;
}
</style>
