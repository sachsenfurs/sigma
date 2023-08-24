<template>
    <div>
        <div class="scrollmenu">
            <ul class="nav nav-tabs">
                <day-tab-component
                    v-for="(entry, index) in uniqueWeekdays"
                    :entry="entry"
                    :key="index"
                >
                </day-tab-component>
            </ul>
            <ul class="nav nav-tabs">
                <room-tab-component
                    v-for="(entry, index) in uniqueRooms"
                    :entry="entry"
                    :key="index"
                ></room-tab-component>
            </ul>
        </div>
        <entry-component
            v-for="(entry, id) in entries"
            :entry="entry"
            :key="entry.id"
        ></entry-component>
    </div>
</template>

<script>
import EntryComponent from "./EntryComponent.vue";
import DayTabComponent from "./DayTabComponent.vue";
import RoomTabComponent from "./RoomTabComponent.vue";

export default {
    components: {
        EntryComponent,
        DayTabComponent,
        RoomTabComponent,
    },
    methods: {
        async getEntries() {
            let events = await axios.get("/table/index");
            this.entries = events.data;
        },
    },
    computed: {
        uniqueWeekdays() {
            const uniqueMap = new Map();
            this.entries.forEach((entry) => {
                const formattedDate = new Date(entry.start).toLocaleDateString();
                if (!uniqueMap.has(formattedDate)) {
                    uniqueMap.set(formattedDate, entry);
                }
            });
            return Array.from(uniqueMap.values());
        },
        uniqueRooms() {
            const uniqueMap = new Map();
            this.entries.forEach((entry) => {
                const roomName = entry.sig_location.name;
                if (!uniqueMap.has(roomName)) {
                    uniqueMap.set(roomName, entry);
                }
            });
            return Array.from(uniqueMap.values());
        },
    },
    data() {
        return {
            entries: [],
        };
    },
    mounted() {
        this.getEntries();
    },
};
</script>
<style scoped></style>
