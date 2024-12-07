import './bootstrap';


// Vue
import EntryList from "./components/TimetableEntries/EntryList.vue";
import SIGCalendar from "./components/SIGCalendar.vue";

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
addVueApp("calendar", SIGCalendar);


// DONT INJECT ALPINE HERE!
// IT WILL DESTROY LIVEWIRE COMPONENTS
// Alpine.js is included with @livewireScripts in the app.blade.php

// import Alpine from 'alpinejs';
// // Await Alpine.js initialization
// document.addEventListener("alpine:init", () => {
// })
// Alpine.start();
//
// window.Alpine = Alpine;
