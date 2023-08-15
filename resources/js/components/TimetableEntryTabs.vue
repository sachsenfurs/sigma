<template>
    <div>
        <!-- Day Nav Tabs -->
        <div class="scrollmenu">
            <ul class="nav nav-tabs">
                <li v-for="(day, index) in days" :key="index" class="nav-item">
                    <a
                        :class="[
                            'nav-link',
                            { active: index === activeTabIndex },
                        ]"
                        data-bs-toggle="tab"
                        :href="'#ConDay' + (index + 1)"
                    >
                        {{ day.start }}
                    </a>
                </li>
                <!-- @foreach ($days as $index => $day)
                <li class="nav-item">
                    <a
                        class="nav-link{{ $loop->first ? ' active' : '' }}"
                        data-bs-toggle="tab"
                        href="#ConDay{{ $index + 1 }}"
                    >
                        {{ Str::upper(\Illuminate\Support\Carbon::parse($day)->locale('en')->dayName) }}
                    </a>
                </li>
                @endforeach -->
            </ul>
        </div>

        <timetable-entry-list />
    </div>
</template>

<script>
export default {
  data() {
    return {
      days: [],
      activeTabIndex: 0 // Index des aktiven Tabs
    };
  },
  mounted() {
    this.ConDays();
  },
  methods: {
    async ConDays() {
      try {
        const response = await axios.get("/table/index"); // API-Endpunkt anpassen
        this.days = response.data;
      } catch (error) {
        console.error('Fehler beim Abrufen der Daten aus der Datenbank:', error);
      }
    }
  }
};
</script>

<style scoped></style>