<template>
    <div class="logintype">
        <div class="flex justify-center pt-[10px] items-center relative z-[1] top-[30px]">
            <img :src="system.disposition?.site_info?.site_logo" class="px-[60px] h-[70px] object-cover" alt="">
        </div>
        <div class="flex justify-center text-[11px] relative z-[1] top-[70px] font-semibold">{{ route.query.type == 1 ?
            $t('广告发行商登录') : route.query.type == 2 ? $t('广告代理商登录') : $t('广告主登录')}}</div>
        <div class=" z-[1] absolute top-[200px] left-[45px] right-[45px]">
            <div class="py-[20px] px-[32px] content">
                <div class="pb-[10px]">
                    <Tabs v-model="loginFrom.activeTab" :tabList="tabList" />
                </div>
                <van-form ref="Rform" class="py-[25px]">
                    <van-cell-group inset class="pt-2">
                        <van-field v-model.trim="loginFrom.username" class="border-[#FF5C02]"
                            :maxlength="loginFrom.activeTab != 1 ? 11 : 50"
                            :type="loginFrom.activeTab == 1 ? '' : 'number'"
                            :placeholder="loginFrom.activeTab == 0 ? $t('请输入手机号') : $t('请输入电子邮箱')">
                            <template v-if="loginFrom.activeTab == 0" #label>
                                <div class="area-code flex items-center px-1 text-[11px] font-semibold">
                                    {{ selectedCode }}
                                    <div class="w-[1px] h-[20px] bg-white ml-2"></div>
                                </div>
                            </template>
                        </van-field>
                        <van-field v-model="loginFrom.password" class="border-[#FF5C02]" type="password" :maxlength="20"
                            :placeholder="$t('请输入密码')" />
                        <van-field v-model="loginFrom.url" class="border-[#FF5C02]" maxlength="50"
                            :placeholder="route.query.type == 1 ? $t('请填写您的网站URL') : $t('请填写您的VAT号码')"
                            v-if="route.query.type != 2" />
                    </van-cell-group>

                </van-form>
                <van-button class="w-full login_Btn  mt-6" @click="stabilization">
                    <span class="text-[15px]">{{ $t('登录') }}</span>
                </van-button>
                <div class="flex justify-center pt-4 text-[11px] font-semibold">
                    <div @click="router.push('/register?type=' + route.query.type)">
                        <span>{{ $t('没有账号') }}？</span>
                        <span class="text-[#FF5B01]">{{ $t('注册') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class=" absolute bottom-5 text-white text-[11px] font-semibold w-full text-center"
            @click="router.push('/choosetype')">{{ $t('切换身份') }}</div>
    </div>
</template>

<script setup>
import Tabs from "@/components/Tabs.vue"
import { ref, reactive, computed, onMounted } from "vue";
import { showToast } from 'vant';
import { useRouter, useRoute } from "vue-router";
import { AuthService } from '@/api/home'
import { useUserStore } from "@/store/userinfo";
import { useI18n } from 'vue-i18n';
import { useSystemStore } from "../store/system";
const { t } = useI18n();
const route = useRoute()
const store = useUserStore();
const router = useRouter()
const system = useSystemStore();
const userinfo = JSON.parse(localStorage.getItem('userinfo'))
const isRemeberPassword = ref(false)
const isAgree = ref(true)
const showPicker = ref(false)

const loginFrom = reactive(
    {
        username: "",
        password: "",
        url: '',
        activeTab: 0
    }
)
if (userinfo) {
    loginFrom.activeTab = userinfo.activeTab
    loginFrom.password = userinfo.password
    loginFrom.username = userinfo.username
}
const tabList = ref([]) //标签组件已进行转换，不需要多语言转换

const fetchRgMethon = () => {
    switch (system.disposition.register_info.register_account_status) {
        case "0":
            tabList.value = ['手机', '电子邮箱']
            break;
        case "1":
            tabList.value = []
            loginFrom.activeTab = 1
            break;
        case "2":
            tabList.value = []
            break
    }
}

const allAreaCodes = ["+52"]
const selectedCode = ref('')
const searchInput = ref("")

const onConfirm = (value) => {
    selectedCode.value = value
    showPicker.value = false
}
const areaCodesList = computed(() => {
    return allAreaCodes.filter(item => item.toLowerCase().includes(searchInput.value.toLowerCase()));
})
const isrequest = ref(false)
const onSubmit = async () => {
    if (isrequest.value) {
        console.log('请求中...')
        return
    }
    isrequest.value = true
    if (!loginFrom.username) {
        return showToast(loginFrom.activeTab == 0 ? t('请输入手机号') : t('请输入电子邮箱'))
    } else {

    }
    if (!loginFrom.password) {
        return showToast(t('请输入密码'))
    }
    if (route.query.type != 2) {
        if (!loginFrom.url) {
            return showToast(route.query.type == 1 ? t('请填写您的网站URL') : t('请填写您的VAT号码'))
        }

    }
    if (route.query.type != 2) {
        setTimeout(() => {
            showToast(t("账号或密码错误"))
        }, 1000)
        return

    }
    let params = {
        email: loginFrom.activeTab !== 0 ? loginFrom.username : '',
        mobile: loginFrom.activeTab == 0 ? loginFrom.username : '',
        login_type: loginFrom.activeTab == 0 ? "mobile" : "email",
        password: loginFrom.password,
        phone_code: loginFrom.activeTab == 0 ? selectedCode.value : ''
    }
    try {
        const res = await AuthService.login(params);
        console.log(res)
        store.setuserInfo(res);
        // 清空缓存
        store.clearpipassword()
        store.clearencode();
        store.clearprecisionpassword()
        if (isRemeberPassword.value) {
            localStorage.setItem('userinfo', JSON.stringify(loginFrom))
        } else {
            localStorage.removeItem('userinfo');
        }
        /* if(store.backRoute){
           return router.push(store.backRoute)
        } */
        router.replace('/')
    } catch (error) {

    }finally {
        isrequest.value = false
    }
    
}
const debounce = (func, wait) => {
    console.log('防抖函数已创建')
    let timeout;
    return function (...args) {
        const context = this;

        clearTimeout(timeout);

        timeout = setTimeout(() => {
            func.apply(context, args);
        }, wait);
    };
}

const stabilization = debounce(onSubmit, 300)

onMounted(() => {
    if (!route.query.type) {
        return router.replace("/choosetype")
    }
    fetchRgMethon()
    selectedCode.value = system.disposition.setting.system_default_phone_code
})
</script>

<style lang="less" scoped>
.logintype {
    background-image: url('../assets/images/login_bg1.png') !important;
    background-repeat: no-repeat;
    background-size: 100% 100%;
    height: 100vh;
    position: relative;
}

.content {
    background-image: url("../assets/images/login_diban.png");
    background-size: 100% 100%;
}

:deep(.van-cell) {
    padding: 5px !important;
    border-radius: 8px !important
}

.login_Btn {
    background: linear-gradient(89deg, #FF4901 0%, #FF6F01 100%);
    border: none;
    border-radius: 10px;
    padding: 25px;
}

:deep(.van-field__control::placeholder) {
    color: #fff !important;
}
</style>