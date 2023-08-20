import Vue from 'vue'
import FeatherIcon from '@core/components/feather-icon/FeatherIcon.vue'
import VueTablerIcons from "vue-tabler-icons";
import { IconsPlugin } from 'bootstrap-vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
Vue.use(IconsPlugin)
Vue.use(VueTablerIcons);
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component(FeatherIcon.name, FeatherIcon)

import {
    faUserGraduate,
    faHeartCircleCheck,
    faPersonSkating,
} from '@fortawesome/free-solid-svg-icons'
import { 
    faCalendarDays,
    faCalendarCheck,
} from '@fortawesome/free-regular-svg-icons'
library.add(
    faUserGraduate,
    faCalendarDays,
    faCalendarCheck,
    faHeartCircleCheck,
    faPersonSkating,
)
