import { createApp } from 'vue';
import { i18nVue } from 'laravel-vue-i18n';

export function mountLocalizedVueApp(selector, component) {
    const target = document.querySelector(selector);

    if (!target) {
        return;
    }

    const app = createApp(component);

    app.use(i18nVue, {
        resolve: (lang) => import(`../../lang/${lang}.json`)
    });

    app.mount(selector);
}
