<template>
        <li class="nav-item">
            <a
                :class="['nav-link', { active: id === activeTabIndex }]"
                data-bs-toggle="tab"
                :href="'#ConDay' + (entry.id + 1)"
            >
                {{ getWeekday(entry.start) }}
            </a>
        </li>
</template>

<script>
export default {
    name: "DayTabComponent",
    props: {
        entry: {
            id: 0,
            start: "",
            formatted_length: "",
            hasLocationChanged: false,
            hasTimeChanged: false,
            cancelled: false,
            sig_event: {
                name: "",
                name_localized: "",
                sig_host: {
                    name: "",
                },
                sig_location: {
                    name: "",
                },
                sig_tags: {
                    description_localized: "",
                },
            },
        },
    },
    methods: {
        getWeekday(dateString) {
            const daysOfWeek = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
            const date = new Date(dateString);
            const weekday = daysOfWeek[date.getDay()];
            return weekday;
        },
    },
    data() {
        return {
            activeTabIndex: 0, // Index des aktiven Tabs
        };
    }
};
</script>

<style scoped></style>
