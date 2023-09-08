<template>
    <div v-if="visible" class="modal fade" :id="modalId" tabindex="-1" role="dialog" :aria-labelledby="labelId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title w-100" :id="labelId">
                        {{ title }}
                    </h4>
                    <button v-if="close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        {{ text }}
                    </p>
                </div>
                <div v-if="footer" class="modal-footer">
                    {{ footer }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Modal} from "bootstrap";

export default {
    name: "MessageModal",
    props: {
        footer: false,
        close: {
            type: Boolean,
            default: true
        },
    },
    data() {
        return {
            modalId: "modal" + Math.round(Math.random() * 99999),
            labelId: "label" + Math.round(Math.random() * 99999),
            visible: false,
            text: "",
            title: "",
        }
    },
    methods: {
        hide() {
            this.visible = false;
        },
        show() {
            this.visible = true;
        }
    },
    computed: {

    },
    mounted() {
        // let m = new Modal('#' + this.modalId);
        // m.show();
    }
}
</script>
<style scoped>

</style>
