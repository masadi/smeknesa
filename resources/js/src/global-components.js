import Vue from 'vue'
import FeatherIcon from '@core/components/feather-icon/FeatherIcon.vue'
import VueTablerIcons from "vue-tabler-icons";
import { IconsPlugin } from 'bootstrap-vue'
Vue.use(IconsPlugin)
Vue.use(VueTablerIcons);
Vue.component(FeatherIcon.name, FeatherIcon)
