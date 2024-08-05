<template>
    <div>
        <div :class="['card mt-3', { 'opacity-50': eventPassed }, { 'bg-dark': eventInternal }]" :id="'event' + entry.id" @click="showInfoModal()" >
            <div class="row g-0 flex-nowrap d-flex">
                <div class="col-lg-2 col-4 d-flex">
                    <div class="card-body align-self-center text-center">
                        <h3 class="text-muted">
                            <i v-if="eventRunning" class="bi bi-record-fill text-danger blink"></i>
                            {{
                                new Date(entry.start).toLocaleTimeString(getActiveLanguage(), {
                                    hour: "numeric",
                                    minute: "numeric",
                                })
                            }}
                        </h3>
                        <h6 class="text-muted">{{ entry.formatted_length }}</h6>
                        <h3 v-if="eventInternal">
                            <span class="badge bg-secondary d-block text-uppercase">{{  $t("Internal") }}</span>
                        </h3>
                        <h3 v-if="entry.new">
                            <span class="badge bg-info d-block text-uppercase">{{ $t("New") }}</span>
                        </h3>
                        <h3 v-if="entry.cancelled">
                            <span class="badge bg-danger d-block text-uppercase">{{  $t("Cancelled") }}</span>
                        </h3>
                        <h3 v-else-if="entry.hasTimeChanged">
                            <span class="badge bg-warning d-block text-uppercase">{{  $t("Changed") }}</span>
                        </h3>
                        <div v-if="entry.sig_event.languages.length > 0" class="mt-3">
                            <img v-for="lang in entry.sig_event.languages" :src="'/icons/' + lang + '-flag.svg'" class="m-1" style="height: 1.2em; opacity: 0.7" :alt="'[' + lang.toUpperCase() + ']'" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-7 d-flex">
                    <div class="card-body px-1 pe-0 align-self-center" style="word-wrap: anywhere">
                        <h1>
                            {{ entry.sig_event.name_localized }}
                        </h1>

                        <p v-if="sigHosts.length > 0" class="card-text">
                            <i class="bi bi-person-circle"></i>
                            {{ sigHosts.join(', ') }}
                        </p>
                        <p>
                            <i class="bi bi-geo-alt"></i>
                            {{ entry.sig_location.name_localized }}
                            <span v-if="entry.hasLocationChanged" class="badge bg-danger">{{  $t("Changed") }}</span>
                        </p>

                        <h4 v-for="tag in entry.sig_event.sig_tags" class="d-inline m-1">
                            <span class="badge my-1 bg-secondary">{{ tag.description_localized }}</span>
                        </h4>
                    </div>
                </div>
                <div class="card-body col-lg-1 col-1 d-flex flex-column justify-content-center text-end ps-0">
                    <a href="#" class="fav-btn text-secondary w-100 justify-content-center" :data-event="entry.id" @click.stop.prevent="toggleFavorite()">
                        <i :class="['fav bi', {'text-danger bi-heart-fill pop': entry.is_favorite, 'bi-heart': !entry.is_favorite}]"></i>
                    </a>
                </div>
            </div>
        </div> <!-- end .card -->
        <entry-modal :id="'eventInfo' + entry.id" :entry="entry" />
    </div>
</template>
<script>
import EntryModal from "./EntryModal.vue";
import {getActiveLanguage} from "laravel-vue-i18n";
import {Modal} from "bootstrap";
import {trans} from "laravel-vue-i18n";
export default {
    name: "EntryComponent",
    methods: {
        getActiveLanguage,
        toggleFavorite() {
            if(!this.favoriteUpdating) {
                let self = this;
                this.favoriteUpdating = true;
                let request;

                if(this.entry.is_favorite) {
                    request = axios.delete("/favorites/" + this.entry.id);
                } else {
                    request = axios.post("/favorites", {
                        timetable_entry_id: this.entry.id
                    });
                }


                request.then((response) => {
                    this.entry.is_favorite = !this.entry.is_favorite;
                    self.favoriteUpdating = false;
                })
                .catch((error) => {
                    if(error.response.status == 401) {
                        this.$emit("alert", trans("You have to be logged in"));
                    }
                })
                .finally(() => {
                    // alert(trans("You have to be logged in"));
                    self.favoriteUpdating = false;
                });
            }
        },
        showInfoModal() {
            let m = new Modal('#eventInfo' + this.entry.id);
            m.show();
        }
    },
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
                sig_hosts: [],
                sig_location: {
                    name_localized: "",
                },
                sig_tags: {
                    description_localized: "",
                },
            },
            is_favorite: false
        }
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
        sigHosts() {
            return this.entry.sig_event.sig_hosts.filter(host => !host.hide).map(host => host.name);
        },
        eventRunning() {
            return !this.entry.cancelled && this.now >= new Date(this.entry.start) && !this.eventPassed;
        },
        eventPassed() {
            return this.now >= new Date(this.entry.end);
        },
        eventInternal() {
            return this.entry.hide;
        }
    },
    data() {
        return {
            now: Date.now(),
            favoriteUpdating: false
        }
    }
};
</script>
<style scoped>

.card {
    cursor: pointer;
}

.blink {
    transition: opacity 1s ease-in-out;
}

.fav {
    font-size: calc(1.305rem + 0.66vw);
    transition: color 0.2s ease-in-out;
}

.pop::before {
    animation: pop 0.2s ease-in;
}

@keyframes pop{
    75%  {transform: scale(1.2);}
    70%  {transform: scale(0.8);}
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
