<template>
    <div class="modal fade" :id="id" tabindex="-1" role="dialog" :aria-labelledby="'eventInfoLabel' + entry?.id" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div v-if="entry" class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title w-100" :id="'eventInfoLabel' + entry.id">
                        {{ entry.sig_event.name_localized }}
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p v-html="description_markdown" />
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
