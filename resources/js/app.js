import '../css/app.css';
import './bootstrap';

import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RoadmapLayout from './Layouts/RoadmapLayout.vue';
import CommentLayout from './Layouts/CommentLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('Link', Link)
            .component('Head', Head)
            .component('AuthenticatedLayout', AuthenticatedLayout)
            .component('RoadmapLayout', RoadmapLayout)
            .component('CommentLayout', CommentLayout)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
