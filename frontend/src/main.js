import Vue from 'vue'
import axios from 'axios'
import App from './App.vue'
import ElementUI from 'element-ui'
import router from './router'
import store from './store'
import { post, fetch, patch, put } from './util/http'
import Validator from 'vue-validator'
// import 'font-awesome/css/font-awesome.min.css'
import 'element-ui/lib/theme-chalk/index.css'
import '@/app.less'

Vue.use(ElementUI);
Vue.use(Validator)

Vue.config.productionTip = false

Vue.prototype.$http = axios;

Vue.prototype.$post = post;
Vue.prototype.$fetch = fetch;
Vue.prototype.$patch = patch;
Vue.prototype.$put = put;

// 全局导航钩子
router.beforeEach((to, from, next) => {
  if (to.meta.requireAuth) {
      let token = window.localStorage.getItem('token');
      if (!token) {
          next({
              path: '/login',
              query: { redirect: to.fullPath } // 将跳转的路由path作为参数，登录成功后跳转到该路由
          });
          return;
      }
  }
  next();
})

new Vue({
  render: h => h(App),
  store,
  router,
}).$mount('#app')
