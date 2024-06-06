<script>
import FullCalendar from '@fullcalendar/vue3'
import resourceTimeGridPlugin from '@fullcalendar/resource-timegrid'
import scrollGridPlugin from '@fullcalendar/scrollgrid'
import localeEn from '@fullcalendar/core/locales/en-gb'
import localeDe from '@fullcalendar/core/locales/de'
import axios from 'axios';
import EntryModal from './TimetableEntries/EntryModal.vue';
import {Modal} from 'bootstrap';
import {getActiveLanguage} from "laravel-vue-i18n";

export default {
    components: {
        EntryModal,
        FullCalendar
    },
    methods: {
        async getEvents() {
            await axios
                .request({
                    url: "/calendar/events",
                    method: "GET",
                    timeout: 30000,
                    signal: AbortSignal.timeout(30000)
                })
                .then((response) => {
                    this.events = response.data;

                    // Events come back ordered by start date
                    this.calendarOptions.validRange.start = this.events[0].start;
                    this.calendarOptions.validRange.end = this.events[this.events.length - 1].end;

                    this.calendarOptions.initialDate = this.events[0].start;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        async getResources() {
            await axios
                .request({
                    url: "/calendar/resources",
                    method: "GET",
                    timeout: 30000,
                    signal: AbortSignal.timeout(30000)
                })
                .then((response) => {
                    this.resources = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        handleEventClick: function(event) {
            const eventId = event.event.id;
            const eventTitle = event.event.title;
            const eventDescription = event.event.extendedProps.sig_event.description_localized;
            this.currentEvent = {
                id: eventId,
                sig_event: {
                    name_localized: eventTitle,
                    description_localized: eventDescription
                }
            };
            const modal = new Modal('#eventInfo');
            modal.show();
        },
    },
    data() {
        let self = this;
        return {
            events: [],
            currentEvent: null,
            resources: [],
            calendarOptions: {
                plugins: [ resourceTimeGridPlugin, scrollGridPlugin ],
                schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                initialView: 'resourceTimeGridDay',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                titleFormat: {
                    day: 'numeric',
                    month: 'long',
                    weekday: 'long'
                },
                locales: [ localeDe, localeEn ],
                locale: getActiveLanguage(),
                slotMinTime: '08:00:00',
                slotMaxTime: '28:00:00',
                nowIndicator: true,
                allDaySlot: false,
                showNonCurrentDates: true,
                defaultTimedEventDuration: '01:00',
                forceEventDuration: true,
                scrollTimeReset: false,
                stickyHeaderDates: true,
                dayMinWidth: 150,
                height: '80vh', // So that the calendar is not too big
                stickyFooterScrollbar: true,
                resources: function(fetchInfo, successCallback, failureCallback) {
                    self.getResources()
                        .then(() => {
                            successCallback(self.resources);
                        });
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    self.getEvents()
                        .then(() => {
                            successCallback(self.events);
                        });
                },
                validRange: {
                    start: '', // Is set when events are fetched
                    end: '' // Is set when events are fetched
                },
                initialDate: new Date(), // Is set when events are fetched
                eventClick: this.handleEventClick,
            }
        }
    }
}
</script>
<template>
    <div class="container">
        <FullCalendar :options="calendarOptions" />
        <entry-modal :entry="currentEvent" :id="'eventInfo'" />
    </div>
</template>

<style>
.fc .fc-scrollgrid-section-sticky > * {
    background: var(--bs-body-bg);
}

.fc-timegrid .fc-scrollgrid,
.fc-timegrid th,
.fc-timegrid td {
    border: 1px solid #3F3F3F;
}

.fc-timegrid .fc-scrollgrid {
    border-right: none;
    border-bottom: none;
}

.fc-v-event {
    background-color: #2C3D4F;
}

.fc-day-today {
    background-color: transparent !important;
}

.fc-timegrid-event-harness-inset .fc-timegrid-event,
.fc-timegrid-event.fc-event-mirror,
.fc-timegrid-more-link {
    box-shadow: none;
}
</style>
