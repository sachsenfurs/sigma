<template>
    <div class="modal fade" :id="id" tabindex="-1" role="dialog" :aria-labelledby="'eventInfoLabel' + entry?.id" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div v-if="entry" class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title w-100" :id="'eventInfoLabel' + entry.id">
                        {{ entry.sig_event.name_localized }}
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" v-if="description_markdown">
                    <div class="row g-3">
                        <div class="col-12">
                            <p v-html="description_markdown" />
                        </div>
                        <div v-if="entry.sig_event" class="col-auto align-items-center mt-1">
                            <span v-for="(host) in entry.sig_event.sig_hosts" class="d-flex align-items-center p-1 pe-2">
                                <img v-if="host.avatar" class="rounded-circle me-2" width="40" height="40" :src="host.avatar" loading="lazy" alt="">
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

</style>
