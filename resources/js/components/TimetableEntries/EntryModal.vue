<template>
    <div class="modal fade" :id="id" tabindex="-1" role="dialog" :aria-labelledby="'eventInfoLabel' + entry?.id" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div v-if="entry" :class="['modal-content', {'bg-purple-800': entry.sig_event.is_private}]">
                <div class="modal-header">
                    <h4 class="modal-title w-100" :id="'eventInfoLabel' + entry.id">
                        <i class="bi bi-lock icon-link" v-if="entry.sig_event.is_private"></i>
                        {{ entry.sig_event.name_localized }}
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" v-if="description_markdown">
                    <div class="row g-3">
                        <div v-if="entry.sig_event.is_private" class="alert alert-warning d-flex align-items-center">
                            <i class="col-auto pe-3 bi bi-exclamation-lg fs-4"></i>
                            <div class="col">
                                {{ $t("This event is not listed in the public schedule and only selected attendees can see this. Be careful sharing this with others as it may contain sensitive information!") }}
                            </div>
                        </div>

                        <div class="col-11 no-p mb-2" v-html="description_markdown">

                        </div>
                        <div class="col-1 text-center align-self-center">
                            <a href="#" class="fav-btn text-secondary w-100 justify-content-center fs-2"
                               :data-event="entry.id"
                               @click.stop.prevent="entry.toggleFavorite(); $emit('updatefavs')">
                                <i :class="['fav bi', {'text-danger bi-heart-fill pop': entry.is_favorite, 'bi-heart': !entry.is_favorite}]"></i>
                            </a>
                        </div>
                        <div v-if="entry.sig_event" class="col-auto align-items-center mt-1">
                            <span v-for="(host) in entry.sig_event.sig_hosts" class="d-flex align-items-center p-1 pe-2">
                                <img v-if="host.avatar_thumb" class="rounded-circle me-2" width="40" height="40" :src="host.avatar_thumb" loading="lazy" alt="">
                                <div v-else>
                                    <i class="bi bi-person-circle fs-4 me-2"></i>
                                </div>

                                <span style="font-size: 1.3em; font-weight: normal">
                                    {{ host.name }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary w-100" :href="link">{{  $t("Show Event") }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { marked } from "marked";
export default {
    name: "EntryModal",
    props: {
        id: null,
        entry: null,
    },
    computed: {
        description_markdown() {
            return marked(this.entry.sig_event.description_localized ?? "");
        },
        link() {
            return '/show/' + this.entry.id;
        }
    }
}
</script>

<style scoped>
.no-p >>> p {
    padding: 0 !important;
    margin: 0 !important;
}

.pop::before {
    animation: pop 0.2s ease-in;
}

@keyframes pop{
    75%  {transform: scale(1.2);}
    70%  {transform: scale(0.8);}
}

</style>
