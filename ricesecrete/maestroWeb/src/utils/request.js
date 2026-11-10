import axios from "axios";
import { showToast } from 'vant';
import router from "@/router";
import { useUserStore } from "@/store/userinfo";
import md5 from 'js-md5';
import { AuthService } from "@/api/home";

const basehost = {
  'localhost': 'https://maestro.mvoag.com', //本地
  'www-maestro.mvoag.com': 'https://maestro.mvoag.com',//测试
  //第一批备用域名
  'maestrotradebr.com': 'https://api.maestroadsbr.com',
  //8.13第二批域名
  'maestribizr.xyz': 'https://api.maestrosbiz.com',
  'maestro-biz-r.top': 'https://api.maestrobizz.com',
  'maestro-biz-r.xyz': 'https://api.maestro-biz.com',
  //8.14 加三个域名
  "maesribs.com": "https://api.maesribizcentral.com",
  "maestribizr.top": "https://api.maestribizr.com",
  "maestribizr.buzz": "https://api.maesribizrr.com",
  //8.19 新增域名 接口取上面的
  'adconnectbr.com': 'https://api.maestribizr.com',
  'adconnectbrazil.com': 'https://api.maestribizr.com',
  'adsglobais.com': 'https://api.maesribizrr.com',
  'adsglobalbr.com': 'https://api.maesribizrr.com',
  'brasildigitalnow.com': 'https://api.maestrosbiz.com',
  'brazildigitaldigital.com': 'https://api.maestrosbiz.com',
  'brazildigitallabs.com': 'https://api.maestrobizz.com',
  'digitahubbrazil.com': 'https://api.maestrobizz.com',
  'digital-hub-brazil.com': 'https://api.maestroadsbr.com',
  'digitalhubbrazils.com': 'https://api.maestroadsbr.com'
}
let baseURL = 'https://api.maestrobr.com'
//根据域名来判断接口地址
let host = window.location.hostname
host = host.replace("www.", "");
baseURL = basehost[host] || baseURL;
const instance = axios.create({
  baseURL: baseURL,
  // withCredentials: true,
  timeout: 30000
});
const Need_sign = ['/api.php/new_finance_product/match', '/api.php/new_finance_product/overdue_match', '/api.php/new_finance_product/get_finance_product_order']
instance.interceptors.request.use(

  config => {
    if (config.method == "get") {
      if (Need_sign.includes(config.url)) {
        config.params = signData(config.params, secret);
      }
      config.params = { ...config.params, lang: "pt" }
    } else {
      if (Need_sign.includes(config.url)) {
        config.data = signData(config.data, secret);
      }
      config.url = config.url + "?lang=pt"
    }
    const store = useUserStore();

    if (store.userInfo?.token) {
      if (config.url == '/api.php/login/refresh_token') {
        config.headers['Authorization'] = store.userInfo?.refresh_token;
      } else {
        config.headers['Authorization'] = store.userInfo?.token;
      }

    }

    config.headers['uuid'] = getUUID('uuid');
    return config
  }

)

instance.interceptors.response.use(
  async response => {
    const { code, data, msg } = response.data
    const { config } = response
    switch (code) {
      case 0:
        if (config.successTips) {
          showToast(msg)
        }
        return data;
      case 200:
        if (config.successTips) {
          showToast(msg)
        }
        return data;
      case 1:
        if (config.successTips) {
          showToast(msg)
        }
        return data;
      case 10001:
        const store = useUserStore();
        // token 过期或无效
        try {
          if (store.userInfo.token) {
            // 调用刷新token接口（假设有 refreshToken 方法）
            const user = await AuthService.refreshToken();
            // 如果刷新成功，user_info 会包含新的 token 信息
            if (user) {
              // 更新 token
              store.setuserInfo(user);
              // 重新发起原请求
              return instance(config); // 重新请求
            } else {
              // 刷新失败，清除信息并跳转
              store.clearUserInfo();
              router.push('/choosetype');
              break;
            }
          } else {
            // 刷新失败，清除信息并跳转
            store.clearUserInfo();
            router.push('/choosetype');
            break;
          }

        } catch (e) {
          // 刷新失败，清除信息并跳转
          const store = useUserStore();
          store.clearUserInfo();
          router.push('/choosetype');
          break;
        }

    }
    if (msg) {
      showToast(msg);
    } else {
      showToast("Request failed, please try again later")
    }

    throw new Error();

  },
  error => {
    return Promise.reject(error);
  }
);

function bin2hex(s) {
  var i,
    l,
    o = "",
    n;
  s += "";
  for (i = 0, l = s.length; i < l; i++) {
    n = s.charCodeAt(i).toString(16);
    o += n.length < 2 ? "0" + n : n;
  }
  return o;
}
const secret = 'akz8VEU!kru6kgk9dhp';
function signData(data, secret) {
  // 拼接时间戳
  const brazilTimestamp = Math.floor(Date.now() / 1000);
  data['timestamp'] = brazilTimestamp;
  // 1. 获取 key 并排序
  const keys = Object.keys(data).sort();
  // 2. 拼接字符串
  const paramStr = keys.map(key => `${key}=${data[key]}`).join('&');
  // 3. 拼接 secret
  // 拼接签名字符串：参数 + secret
  const strToSign = paramStr + secret;
  // 4. 生成 md5
  const sign = md5(strToSign);
  // 5. 加入 sign
  return { ...data, sign, timestamp: brazilTimestamp };
}
function getUUID(domain) {
  var canvas = document.createElement("canvas");
  var ctx = canvas.getContext("2d");
  var txt = domain;
  ctx.textBaseline = "top";
  ctx.font = "14px 'Arial'";
  ctx.textBaseline = "tencent";
  ctx.fillStyle = "#f60";
  ctx.fillRect(125, 1, 62, 20);
  ctx.fillStyle = "#069";
  ctx.fillText(txt, 2, 15);
  ctx.fillStyle = "rgba(102, 204, 0, 0.7)";
  ctx.fillText(txt, 4, 17);
  return md5(canvas.toDataURL())
}

export default instance;
