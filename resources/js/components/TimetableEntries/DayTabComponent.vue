<template>
    <ul class="nav nav-underline d-flex bg-body flex-nowrap navbar-nav-scroll" ref="scrollTab">
        <li v-for="(date, dateIndex) in Array.from(days.values())" :key="date" class="nav-item flex-fill p-1 py-2">
            <a
                :class="['nav-link text-center', { active: activeDayIndex == dateIndex }]"
                :href="'#day' + dateIndex"
                @click="switchDay(dateIndex, date, this)"
            >
                <h3>
                    {{ getWeekday(date) }}
                </h3>
            </a>
        </li>
    </ul>
</template>

<script>
export default {
    name: "DayTabComponent",
    props: {
        days: [],
        activeDayIndex: 0,
    },
    methods: {
        getWeekday(date) {
            return new Date(date).toLocaleDateString("de", { weekday: 'long' })
        },
        switchDay(dateIndex, date, el) {
            this.$emit("scrollToDay", dateIndex);
            this.$emit("setActiveTab", dateIndex);
            let navOffset = this.$el.getBoundingClientRect().left;
            let targetOffset = event.target.getBoundingClientRect().left;
            this.$el.scrollTo(targetOffset-navOffset, 0);
        }
    }
};
</script>

<style scoped></style>
