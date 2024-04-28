import axios from "axios";
export const form = (data: object) => ({
    loading: false,
    response: null as unknown,
    editId: null,
    ...data,

    async submit(event: SubmitEvent) {
        const formElements: HTMLFormElement = event.target as HTMLFormElement;
        const formData: FormData = new FormData(formElements);
        this.startLoading(formElements);

        let method: string = formElements.method;
        let action: string = formElements.action;

        // @ts-ignore
        this.response = await axios[method](action, formData)
            .then(function(response: any) {
                location.reload();
            })
            .catch(function(error: any) {
                return error.response?.data;
            });

        this.finishLoading(formElements);
    },

    startLoading(form: HTMLFormElement) {
        this.loading = true;
        form.querySelectorAll("input,textarea,button,fieldset,select").forEach((e: HTMLFormElement) => e.disabled=true);
    },
    finishLoading(form: HTMLFormElement) {
        this.loading = false;
        form.querySelectorAll("input,textarea,button,fieldset,select").forEach((e: HTMLFormElement) => e.disabled=false);
    }
})
