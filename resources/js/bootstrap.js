window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

import Vue from 'vue';
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import moment from 'moment';
import { configure, ValidationProvider, ValidationObserver, extend, localize } from 'vee-validate'
import es from 'vee-validate/dist/locale/es.json'
import * as rules from 'vee-validate/dist/rules'
import VueSweetalert2 from 'vue-sweetalert2';


configure({
    classes: {
        valid: 'is-valid',
        invalid: 'is-invalid',
        dirty: 'is-dirty'
    }
})

Object.keys(rules).forEach((i) => {
    // eslint-disable-next-line import/namespace
    extend(i, rules[i])
})
extend('decimal', {
    params: ['decimals'],
    validate: (value, { decimals }) => {
        if (!isNaN(value)) {
            try {
                if (Math.floor(value) !== value) {
                    return value.toString().split('.')[1].length <= decimals
                }
                return true
            } catch (e) {
                return true
            }
        } else {
            return false
        }
    },
    message: 'El campo {_field_} debe tener máximo {decimals} decimales'
})
localize('es', es)
Vue.component('ValidationProvider', ValidationProvider)
Vue.component('ValidationObserver', ValidationObserver)


moment.locale('es')

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueSweetalert2);

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
