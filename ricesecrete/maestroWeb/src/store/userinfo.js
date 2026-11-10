import { defineStore } from "pinia";
import { ref,reactive } from "vue";
import { AuthService } from "@/api/home";
export const useUserStore = defineStore('user',()=>{
    const is_login = ref(false)
    const userInfo = ref({})
    //1普通广告库存 2精准广告库存 3体验区广告库存
    const is_precision = ref(1)
    //当前类型,区分匹配页面的颜色,默认为2 未推广高高库存
    const currentType = ref(2)
    //匹配体验流量包密码到期时间
    const pipeipassword = ref({
        bianma:'',
        expire_time:'',
    })
    //匹配精准广告库存
    const precisionpassword = ref({
        bianma:'',
        expire_time:'',
    })
    //广告库存编码是否过期
    const encode = ref({
        bianma:'',
        expire_time:'',
    })
    //余额为负数是否进行弹框提示
    const is_show_balance_warning = ref("0")
    const backRoute = ref("")
    const set_is_precision = (num) => {
        is_precision.value = num
    }
    const setpipassword = (pas,time) => {
        pipeipassword.value.bianma = pas
        pipeipassword.value.expire_time = time
    }
    const clearpipassword = () => {
        pipeipassword.value.password = ''
        pipeipassword.value.expire_time = ''
    }
    const setprecisionpassword = (pas,time) => {
        precisionpassword.value.bianma = pas
        precisionpassword.value.expire_time = time
    }
    const clearprecisionpassword = () => {
        precisionpassword.value.password = ''
        precisionpassword.value.expire_time = ''
    }
    const setencode = (pas,time) => {
        encode.value.bianma = pas
        encode.value.expire_time = time
    }
    const clearencode = () => {
        encode.value.password = ''
        encode.value.expire_time = ''
    }
    const setuserInfo = (user) => {
        userInfo.value = user
        is_login.value = true
    }
    const clearUserInfo = ()=>{
        is_login.value = false
        userInfo.value = {}
    }
    const getUserInfo = async() => {
        const res = await AuthService.userinfo()
        if(userInfo.value){
            userInfo.value.user_info = res;
        }
    }
    const set_currentType = (type) => {
        currentType.value = type
    }
    return {
        userInfo,
        backRoute,
        is_login,
        pipeipassword,
        precisionpassword,
        encode,
        is_precision,
        currentType,
        is_show_balance_warning,
        clearUserInfo,
        setuserInfo,
        getUserInfo,
        setpipassword,
        clearpipassword,
        setprecisionpassword,
        clearprecisionpassword,
        set_is_precision,
        setencode,
        clearencode,
        set_currentType,
    }
},
// 持久化存储
{
    persist: true,
}

)