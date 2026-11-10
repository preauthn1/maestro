import { createI18n } from 'vue-i18n'
import pt from './pt'
import cn from './cn'

const messages = { pt,cn };
const i18n = createI18n({
  locale: 'pt', 
  fallbackLocale: 'pt',
  messages,
  legacy: false,
  // missing: (locale, key) => {
  //   console.log(key);}
});
export default i18n;
