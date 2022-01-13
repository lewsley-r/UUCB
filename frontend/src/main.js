import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import { BootstrapVue, IconsPlugin, FormFilePlugin, ButtonPlugin, AlertPlugin, FormTextareaPlugin, FormTagsPlugin, ListGroupPlugin, PaginationPlugin, SpinnerPlugin } from 'bootstrap-vue'

// BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'


Vue.config.productionTip = false

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(ButtonPlugin)
Vue.use(FormFilePlugin)
Vue.use(AlertPlugin)
Vue.use(FormTextareaPlugin)
Vue.use(FormTagsPlugin)
Vue.use(ListGroupPlugin)
Vue.use(PaginationPlugin)
Vue.use(SpinnerPlugin)




new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

