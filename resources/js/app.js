import "./bootstrap";

// bootstrap
import "bootstrap-icons/font/bootstrap-icons.css";

// jQuery
import $ from "jquery";
window.$ = window.jQuery = $;
import "jquery-ui/ui/widgets/autocomplete";

// Vue
import { createApp } from "vue";
import ExampleComponent from "./components/ExampleComponent.vue";
import EntryComponent from "./components/TimetableEntrys/EntryComponent.vue";
import EntryList from "./components/TimetableEntrys/EntryList.vue";
import DayTabComponent from "./components/TimetableEntrys/DayTabComponent.vue";
import RoomTabComponent from "./components/TimetableEntrys/RoomTabComponent.vue";
let app = createApp({
    components: {
        ExampleComponent,
        EntryComponent,
        EntryList,
        DayTabComponent,
        RoomTabComponent
    },
}).mount("#app");
