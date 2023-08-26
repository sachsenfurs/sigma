<template>
    <div>
        <div class="sticky-top" ref="dayTabs">
            <day-tab-component :days="uniqueWeekdays"
                               :activeDayIndex="activeDayIndex"
                                @set-active-tab="(t) => this.activeDayIndex = t"
                                @scroll-to-day="(n) => scrollToDay(n)"
            />
        </div>
        <div v-for="(date, dateIndex) in Array.from(uniqueWeekdays.values())" class="entrySlot">
            <hr>
            <h1 :ref="dateIndex" class="text-center">
                {{ new Date(date).toLocaleDateString("de", { weekday: "long", day: "numeric", month: "2-digit" }) }}
            </h1>
            <entry-component
                v-for="(entry, id) in getEntriesByDate(date)"
                :entry="entry"
                :key="entry.id"
                ref="entrySlot"
                :data-date-index="dateIndex"
            ></entry-component>
        </div>
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
            this.getEntriesByDate();
            this.entries = events.data;
        },

        getEntriesByDate(date) {
            return this.entries.filter(e => (new Date(e.start).toDateString() === new Date(date).toDateString()))
        },
        scrollToDay(dateIndex) {
            let firstDayEvent = this.$refs[dateIndex][0];
            let top = firstDayEvent.nextElementSibling.offsetTop;
            let margin = this.$refs['dayTabs'].offsetHeight;
            window.scrollTo(0, top-margin-15);
        },
        handleScroll(event) {
            let lastEl = null;
            let margin = this.$refs['dayTabs'].offsetHeight;
            this.$refs['entrySlot'].forEach(function(entry) {
                let marginHeading = entry.$el.previousElementSibling?.offsetHeight;
                if(window.scrollY > entry.$el.offsetTop - margin - marginHeading) {
                    lastEl = entry.$el;
                }
            });
            this.activeDayIndex = lastEl?.dataset.dateIndex || 0;
        }
    },
    computed: {
        uniqueWeekdays() {
            let days = new Map();
            this.entries.forEach((entry) => {
                let date_id = new Date(entry.start).toLocaleDateString();
                if(!days.has(date_id)) {
                    days.set(date_id, new Date(entry.start));
                }
            });
            return days;
        },
        // uniqueRooms() {
        //     const uniqueMap = new Map();
        //     this.entries.forEach((entry) => {
        //         const roomName = entry.sig_location.name;
        //         if (!uniqueMap.has(roomName)) {
        //             uniqueMap.set(roomName, entry);
        //         }
        //     });
        //     return Array.from(uniqueMap.values());
        // },
    },
    data() {
        return {
            entries: [],
            activeDayIndex: location.hash.replace("#day", "") || 0,
        };
    },
    mounted() {
        this.getEntries().then(() => {
            let i = location.hash.replace("#day", "") || 0;
            this.scrollToDay(i);
        })

        window.addEventListener("scroll", this.handleScroll);
    }
};
</script>
<style scoped>

.entrySlot:last-child {
    min-height: 100vh;
}

</style>
