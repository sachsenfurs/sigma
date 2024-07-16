<template>
    <li class="nav-item">
            <a
                :class="['nav-link', { active: id === activeTabIndex }]"
                data-bs-toggle="tab"
                :href="'#'+ entry.sig_location.name + (entry.sig_location.id + 1)"
            >
                {{ entry.sig_location.name }}
            </a>
        </li>
</template>

<script>
export default {
    name: "RoomTabComponent",
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
                sig_hosts: [],
                sig_location: {
                    name: "",
                },
                sig_tags: {
                    description_localized: "",
                },
            },
        },
    },
    data() {
        return {
            activeTabIndex: 0, // Index des aktiven Tabs
        };
    }
};
</script>
