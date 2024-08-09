<script>
import FullCalendar from '@fullcalendar/vue3'
import resourceTimeGridPlugin from '@fullcalendar/resource-timegrid'
import resourceTimelinePlugin from '@fullcalendar/resource-timeline'
import interactionPlugin from '@fullcalendar/interaction'
import scrollGridPlugin from '@fullcalendar/scrollgrid'
import localeEn from '@fullcalendar/core/locales/en-gb'
import localeDe from '@fullcalendar/core/locales/de'
import EntryModal from './TimetableEntries/EntryModal.vue';
import {Modal} from 'bootstrap';
import {getActiveLanguage, wTrans} from "laravel-vue-i18n";

export default {
    components: {
        EntryModal,
        FullCalendar
    },
    methods: {
        async getEvents() {
            const res = await fetch('/schedule/index', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            return await res.json();
        },
        async getResources() {
            const res = await fetch('/calendar/resources', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            return await res.json();
        },
        handleEventClick: function(event) {
            this.currentEvent = {id: event.event.id, ...event.event.extendedProps};
            const modal = new Modal('#eventInfo');
            modal.show();
        },
    },
    data() {
        return {
            currentEvent: null,
            currentView: 'resourceTimeGridDay',
            calendarOptions: {
                plugins: [ resourceTimeGridPlugin, resourceTimelinePlugin, scrollGridPlugin, interactionPlugin ],
                schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
                initialView: 'resourceTimeGridDay',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                footerToolbar: {
                    left: 'toggleView',
                },
                titleFormat: (info) => new Date(info.date.marker)
                    .toLocaleDateString(
                        getActiveLanguage(),
                        {
                            day: 'numeric',
                            month: 'long',
                            weekday: 'long'
                        }
                    ),
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
                stickyFooterScrollbar: true,
                resources: [],
                events: [],
                resourceAreaHeaderContent: wTrans('Location'),
                validRange: {
                    start: '', // Is set when events are fetched
                    end: '' // Is set when events are fetched
                },
                initialDate: new Date(), // Is set when events are fetched
                eventClick: this.handleEventClick,
                customButtons: {
                    toggleView: {
                        text: wTrans('Toggle View'),
                        click: () => {
                            this.currentView = this.currentView === 'resourceTimeGridDay' ? 'resourceTimelineDay' : 'resourceTimeGridDay';
                            this.$refs.fullCalendar.getApi().changeView(this.currentView, this.$refs.fullCalendar.getApi().currentDate);
                        },
                    }
                },
            }
        }
    },
    async mounted() {
        const calResources = await this.getResources();
        calResources.map(function(res) {
            res.title           = res.name_localized;
            if(res.description_localized != res.name_localized)
                res.title       = res.name_localized + " - " + res.description_localized;

            return res;
        });
        const calEvents = await this.getEvents();
        calEvents.map(function(event) {
            event.resourceId        = event.sig_location.id;
            event.title             = (event.is_favorite ? "❤ " : "") + event.sig_event.name_localized;
            event.backgroundColor   = event.eventColor[0] ?? null;
            event.borderColor       = event.eventColor[1] ?? null;
            return event;
        });

        this.calendarOptions.resources = calResources;
        this.calendarOptions.events = calEvents;

        // Filter resources not used (must be done, after the events have been loaded)
        this.calendarOptions.filterResourcesWithEvents = true;

        const startDate = Date.parse(calEvents[0].start);
        const endDate = Date.parse(calEvents[calEvents.length - 1].end);

        // Events come back ordered by start date
        this.calendarOptions.validRange.start = startDate;
        this.calendarOptions.validRange.end = endDate;
        this.$refs.fullCalendar.getApi().gotoDate(new Date(calEvents[0].start));

        const daysInMSecs = 1000 * 60 * 60 * 24;
        const days = Math.round((endDate - startDate) / daysInMSecs);
        const calendarApi = this.$refs.fullCalendar.getApi();
        for (let i = 0; i < days; i++) {
            const day = new Date(startDate + (i * daysInMSecs));
            this.calendarOptions.customButtons[`day${i + 1}`] = {
                text: new Intl.DateTimeFormat(getActiveLanguage(), { weekday: 'short' }).format(day),
                click: () => {
                    calendarApi.changeView(this.currentView, day);
                }
            }
            this.calendarOptions.headerToolbar.right += `day${i + 1},`;
        }
        if (days > 0) {
            // Remove trailing comma
            this.calendarOptions.headerToolbar.right = this.calendarOptions.headerToolbar.right.slice(0, -1);
        }
    }
}
</script>
<template>
    <FullCalendar ref="fullCalendar" :options="calendarOptions" />
    <entry-modal :entry="currentEvent" :id="'eventInfo'" />
</template>

<style>
.fc .fc-scrollgrid-section-sticky > * {
    background: var(--bs-body-bg);
}
.fc {
    user-select: none;
    height: 100vh;
}

.fc-timeline .fc-scrollgrid,
.fc-timeline th,
.fc-timeline td,
.fc-timegrid .fc-scrollgrid,
.fc-timegrid th,
.fc-timegrid td {
    border: 1px solid #3F3F3F;
    font-weight: normal;
}

.fc-timeline .fc-scrollgrid,
.fc-timegrid .fc-scrollgrid {
    border-right: none;
    border-bottom: none;
}

.fc-timeline-slot-frame a {
    color: #FFF;
    text-decoration: none;
}

.fc-v-event,
.fc-timeline-event {
    background-color: #2C3D4F;
}

.fc-timeline-event {
    border-radius: 3px;
}

.fc-day-today {
    background-color: transparent !important;
}

.fc-timegrid-event-harness-inset .fc-timegrid-event,
.fc-timegrid-event.fc-event-mirror,
.fc-timegrid-more-link,
.fc-timeline-event {
    box-shadow: none;
    cursor: pointer;
}
</style>
