<template>
    <div>
        <timetable-entry-component v-for="(entry, id) in entries" :entry="entry" :key="entry.id"></timetable-entry-component>
    </div>


</template>

<script>
import timetableEntryComponent from "./TimetableEntryComponent.vue";
export default  {
    components: {
        timetableEntryComponent
    },
    methods: {
        async getEntries() {
            let events = await axios.get("/table/index") ;
            // console.log(events.data);
            this.entries = events.data;
        }
    },
    data() {
        return {
            entries: this.getEntries(),
        }
    }

}
</script>
<style scoped>

</style>
