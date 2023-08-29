import './bootstrap';

// bootstrap
import 'bootstrap-icons/font/bootstrap-icons.css';

import $ from 'jquery';
window.$ = window.jQuery = $;
import 'jquery-ui/ui/widgets/autocomplete';

// Vue
import { createApp } from 'vue';
import RoomTabComponent from "./components/TimetableEntries/RoomTabComponent.vue";
import EntryComponent from "./components/TimetableEntries/EntryComponent.vue";
import EntryList from "./components/TimetableEntries/EntryList.vue";
import DayTabComponent from "./components/TimetableEntries/DayTabComponent.vue";

import { i18nVue } from 'laravel-vue-i18n'

$(document).ready(function() {
    if($('#app').length > 0) {
        let app = createApp({
            components: {
                EntryComponent,
                EntryList,
                DayTabComponent,
                RoomTabComponent,
            }
        })
        .use(i18nVue, {
            resolve: (lang) => import(`../../lang/${lang}.json`)
        })
        .mount("#app");
    }
});
