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
import {getActiveLanguage} from "laravel-vue-i18n";

export default {
    components: {
        EntryModal,
        FullCalendar
    },
    methods: {
        async getEvents() {
            const res = await fetch('/calendar/events', {
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
                resources: [],
                events: [],
                validRange: {
                    start: '', // Is set when events are fetched
                    end: '' // Is set when events are fetched
                },
                initialDate: new Date(), // Is set when events are fetched
                eventClick: this.handleEventClick,
                customButtons: {
                    toggleView: {
                        text: getActiveLanguage() === 'de' ? 'Ansicht wechseln' : 'Toggle View',
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
        const calEvents = await this.getEvents();
        this.calendarOptions.resources = calResources;
        this.calendarOptions.events = calEvents;

        const startDate = Date.parse(calEvents[0].start);
        const endDate = Date.parse(calEvents[calEvents.length - 1].end);

        // Events come back ordered by start date
        this.calendarOptions.validRange.start = startDate;
        this.calendarOptions.validRange.end = endDate;

        this.calendarOptions.initialDate = calEvents[0].start;

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
    <div class="container-lg">
        <FullCalendar ref="fullCalendar" :options="calendarOptions" />
        <entry-modal :entry="currentEvent" :id="'eventInfo'" />
    </div>
</template>

<style>
.fc .fc-scrollgrid-section-sticky > * {
    background: var(--bs-body-bg);
}
.fc {
    user-select: none;
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
.fc-timegrid-more-link {
    box-shadow: none;
    cursor: pointer;
}
</style>
