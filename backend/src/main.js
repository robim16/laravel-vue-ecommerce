import {createApp} from 'vue'
import store from './store'
import router from './router'
import './index.css';
import currencyUSD from './filters/Currency'

import App from './App.vue'

const app = createApp(App);

app
  .use(store)
  .use(router)
  .mount('#app')
;

app.config.globalProperties.$filters = {
  currencyUSD
}