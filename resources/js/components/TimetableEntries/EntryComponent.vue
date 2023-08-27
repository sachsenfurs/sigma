<template>
    <div>
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
                    <div class="card-body px-1 pe-0 align-self-center ">
                        <h1>
                            <a data-bs-toggle="modal" :data-bs-target="'#eventInfo' + entry.id" href="#" class="text-decoration-none" :id="'event' + entry.id">
                                {{ entry.sig_event.name_localized }}
                            </a>
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

                        <h4 v-for="tag in entry.sig_event.sig_tags" class="d-inline m-1">
                            <span class="badge my-1 bg-secondary">{{ tag.description_localized }}</span>
                        </h4>
                    </div>
                </div>
                <div class="card-body col-lg-1 col-1 d-flex flex-column justify-content-center ps-0">
    <!--                <a class="fav-btn text-secondary align-self-center w-100 text-end" data-bs-toggle="modal" :data-bs-target="'#eventInfo' + entry.id">-->
    <!--                    <i class="bi bi-info-circle" style="font-size: calc(1.305rem + 0.66vw)"></i>-->
    <!--                </a>-->
                    <a class="fav-btn text-secondary align-self-center w-100 text-end" :data-event="entry.id">
                        <i class="bi bi-heart" style="font-size: calc(1.305rem + 0.66vw)"></i>
                    </a>
                </div>
            </div>
        </div> <!-- end .card -->
        <entry-modal :id="'eventInfo' + entry.id" :entry="entry" />
    </div>
</template>
<script>
import EntryModal from "./EntryModal.vue";

export default {
    name: "EntryComponent",
    components: {EntryModal},
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

@media only screen and (max-width: 400px) {
    h1 {
        font-size: calc(1rem + 0.5vw)
    }
    h2 {
        font-size: calc(1rem + 0.4vw)
    }
    h3 {
        font-size: calc(1rem + 0.3vw)
    }
    h4 {
        font-size: calc(1rem + 0.2vw)
    }
}
</style>
