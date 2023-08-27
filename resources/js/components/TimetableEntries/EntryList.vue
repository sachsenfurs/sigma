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
import axios from "axios";

export default {
    components: {
        EntryComponent,
        DayTabComponent,
        RoomTabComponent,
    },
    methods: {
        async getEntries() {
            let self = this;
            await axios
                .request({
                    url: "/table/index",
                    method: "GET",
                    timeout: 30000,
                    signal: AbortSignal.timeout(30000)
                })
                .then((response) => {
                    this.lastRefresh = new Date();
                    this.entries = response.data;
                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    setTimeout(self.checkRefreshEntries, 60000);
                });
        },
        checkRefreshEntries() {
            // last refresh older than 60 sec?
            if(Math.abs(new Date() - this.lastRefresh) / 1000 > 60) {
                this.getEntries();
            }
        },
        getEntriesByDate(date) {
            return this.entries.filter(e => (new Date(e.start).toDateString() === new Date(date).toDateString()))
        },
        getMostRecentEvent() {
            let firstEvent = false;
            this.entries.forEach((entry) => {
                if(firstEvent)
                    return;
                if(new Date(entry.start) <= new Date() && new Date(entry.end) >= new Date()) {
                    firstEvent = entry;
                }
            });
            return firstEvent;
        },
        scrollToDay(dateIndex) {
            this.scrollActive = true;
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
            if(!this.scrollActive) // debounce
                this.activeDayIndex = lastEl?.dataset.dateIndex || 0;
        },
        handleScrollEnd() {
            this.scrollActive = false;
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
    },
    data() {
        return {
            entries: [],
            activeDayIndex: location.hash.replace("#day", "") || 0,
            scrollActive: false,
            lastRefresh: new Date(),
        };
    },
    mounted() {
        this.getEntries().then(() => {
            let i = location.hash.replace("#day", "") || 0;
            let conStarted = this.getEntriesByDate(new Date()).length > 0;
            if(conStarted) {
                let entry = this.getMostRecentEvent();
                document.getElementById('event' + entry.id).scrollIntoView({ block: "center"});
            } else {
                this.scrollToDay(i);
            }
        })

        window.addEventListener("scroll", this.handleScroll);
        window.addEventListener("scrollend", this.handleScrollEnd);
        window.addEventListener("focus", this.checkRefreshEntries);
    }
};
</script>
<style scoped>

.entrySlot:last-child {
    min-height: 100vh;
}

</style>
