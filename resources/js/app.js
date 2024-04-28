import './bootstrap';

// bootstrap
import 'bootstrap-icons/font/bootstrap-icons.css';


// Vue
import EntryList from "./components/TimetableEntries/EntryList.vue";

import {i18nVue} from 'laravel-vue-i18n'
import {createApp} from "vue";

const addVueApp = (id, component) => {
    const app = createApp(component);
    app.use(i18nVue, {
        resolve: (lang) => import(`../../lang/${lang}.json`)
    })
    app.mount(`#${id}`);
}

addVueApp("app", EntryList);


import Alpine from 'alpinejs';
import { form } from "./alpine/form"
import { input } from "./alpine/input"
// Await Alpine.js initialization
document.addEventListener("alpine:init", () => {
    Alpine.data("form", form)
    Alpine.data("input", input)
})
Alpine.start();

window.Alpine = Alpine;
