import {trans} from "laravel-vue-i18n";

export default class Entry {
    constructor(data) {
        Object.assign(this, data);
    }

    id = 0;
    start = "";
    formatted_length = "";
    hasLocationChanged = false;
    hasTimeChanged = false;
    cancelled = false;
    sig_event = {
        name: "",
        name_localized: "",
        sig_hosts: [],
        sig_location: {
            name_localized: "",
        },
        sig_tags: {
            description_localized: "",
        },
        languages: [],
    };
    sig_location = {};
    is_favorite = false;

    #favoriteUpdating = false;

    toggleFavorite() {
        if(!this.favoriteUpdating) {
            let self = this;
            this.favoriteUpdating = true;
            let request;

            if(this.is_favorite) {
                request = axios.delete("/favorites/" + this.id);
            } else {
                request = axios.post("/favorites", {
                    timetable_entry_id: this.id
                });
            }


            request.then((response) => {
                this.is_favorite = !this.is_favorite;
                self.favoriteUpdating = false;
            })
            .catch((error) => {
                if(error.response.status === 401) {
                    alert(trans("You have to be logged in"));
                }
            })
            .finally(() => {
                // alert(trans("You have to be logged in"));
                self.favoriteUpdating = false;
            });
        }
    }
}
