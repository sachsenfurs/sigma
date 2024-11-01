<template>
    <div class="user-select-none">
        <div class="sticky-top container-fluid p-0 z-2" style="max-width: 1300px" id="dayTabs" ref="dayTabs">
            <day-tab-component :days="uniqueWeekdays"
                               :activeDayIndex="activeDayIndex"
                                @set-active-tab="(t) => this.activeDayIndex = t"
                                @scroll-to-day="(n) => scrollToDay(n)"
            />
        </div>
        <div class="container-md">
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
<!--        <message-modal title="tester" text="test" />-->
    </div>
</template>

<script>
import EntryComponent from "./EntryComponent.vue";
import DayTabComponent from "./DayTabComponent.vue";
import RoomTabComponent from "./RoomTabComponent.vue";
import MessageModal from "../MessageModal.vue";

import axios from "axios";
export default {
    components: {
        MessageModal,
        EntryComponent,
        DayTabComponent,
        RoomTabComponent,
    },
    methods: {
        async getEntries() {
            let self = this;
            await axios
                .request({
                    url: "/schedule/index",
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
                if(new Date(entry.start) <= new Date()) {
                    firstEvent = entry;
                }
            });
            return firstEvent || this.entries[0];
        },
        scrollToDay(dateIndex) {
            let firstDayEvent = this.$refs[dateIndex][0];
            let top = firstDayEvent.nextElementSibling.offsetTop;
            let margin = (this.$refs['dayTabs'].offsetHeight*2)+35;
            this.scrollElement.scrollTo({
                top: top-margin-15,
                left: 0,
                behavior: "smooth",
            });
        },
        handleScroll(event) {
            let lastEl = null;
            let margin = (this.$refs['dayTabs'].offsetHeight*2)+25;
            let scrollElement = this.scrollElement;
            this.$refs['entrySlot'].forEach(function(entry) {
                let marginHeading = entry.$el.previousElementSibling?.offsetHeight;
                if(scrollElement.scrollTop ?? scrollElement.scrollY > entry.$el.offsetTop - margin - marginHeading) {
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
    },
    data() {
        return {
            entries: [],
            activeDayIndex: location.hash.replace("#day", "") || 0,
            lastRefresh: new Date(),
            alert: "",
            scrollElement: window,
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

        this.scrollElement.addEventListener("scroll", this.handleScroll);
        this.scrollElement.addEventListener("focus", this.checkRefreshEntries);
    }
};
</script>
<style scoped>

.entrySlot:last-child {
    min-height: 100vh;
}
#dayTabs::before {
    position: relative;
    left: 0;
    width: 100%;
}

</style>
