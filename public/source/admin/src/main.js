import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import ViewUI from 'view-design'
import 'view-design/dist/styles/iview.css'
import i18n from '@/locale'
import './permission'
import axios from 'axios'
import VCharts from 'v-charts'
import api from './api/Api'
// import { avatar } from './api/Global'

//tinymce
import tinymce from 'tinymce/tinymce'
import Editor from '@tinymce/tinymce-vue'
import 'tinymce/themes/silver'

axios.defaults.baseURL = ''; // 设置域名
axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
axios.defaults.headers.get['Content-Type'] = 'application/x-www-form-urlencoded';
axios.defaults.headers.delete['Content-Type'] = 'application/x-www-form-urlencoded';
// axios.defaults.headers.get['Content-Type'] = 'application/x-www-form-urlencoded';
// axios.defaults.headers.get['Content-Type'] = 'application/x-www-form-urlencoded';
//转换成form的方法  key=value
axios.defaults.transformRequest = [function (data) {
  let ret = ''
  for (let it in data) {
    ret += encodeURIComponent(it) + '=' + encodeURIComponent(data[it]) + '&'
  }
  return ret
}]
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
// axios.defaults.headers['Content-Type'] = 'application/x-www-form-urlencoded'; // 设置数据传输类型
//
Vue.config.productionTip = false
Vue.use(ViewUI, {
  i18n: (key, value) => i18n.t(key, value)
})
Vue.use(VCharts)
Vue.prototype.$axios = axios
Vue.prototype.api = api

// 挂载全局使用的方法
// Vue.prototype.content = '';

new Vue({
  router,
  store,
  i18n,
  render: h => h(App)
}).$mount('#app')
