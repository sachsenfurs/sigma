import './bootstrap';

// bootstrap
import 'bootstrap-icons/font/bootstrap-icons.css';

import $ from 'jquery';
window.$ = window.jQuery = $;
import 'jquery-ui/ui/widgets/autocomplete';
// Vue
import { createApp } from 'vue';
import ExampleComponent from './components/ExampleComponent.vue'
import TimetableEntryComponent from "./components/TimetableEntryComponent.vue";
import TimetableEntryList from "./components/TimetableEntryList.vue";

$(document).ready(function() {
    if($('#app').length > 0) {
        let app = createApp({
            components: {
                ExampleComponent,
                TimetableEntryComponent,
                TimetableEntryList
            }
        }).mount("#app");
    }
});
