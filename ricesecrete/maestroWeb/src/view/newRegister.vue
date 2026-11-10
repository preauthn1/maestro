<template>
    <div class="logintype ">
        <div class="flex justify-center pt-[10px] items-center relative z-[1] top-[30px]">
            <img :src="system.disposition?.site_info?.site_logo" class="px-[60px] h-[70px] object-cover" alt="">
        </div>
        <div class="flex justify-center text-[11px] relative z-[1] top-[70px] font-semibold">{{ route.query.type == 1 ? $t('广告发行商注册') : route.query.type == 2 ?
            $t('广告代理商注册') : $t('广告主注册') }}</div>
        <div class="pb-[50px]">
            <div class=" absolute z-[1] left-[45px] right-[45px] top-[200px]">
                <div class="py-[20px] px-[32px] content">
                    <div class="pb-[10px]">
                        <Tabs v-model="activeTab" :tabList="tabList" @tabChange="onTabChange"/>
                    </div>
                    <van-form ref="Rform" @submit="onSubmit">
                        <van-cell-group inset class="pt-2">
                            <van-field v-model.trim="registerForm.username" :maxlength="activeTab != 1 ? 11 : 50"
                                :type="activeTab == 1 ? '' : 'number'"
                                :placeholder="activeTab == 0 ? $t('请输入手机号') : $t('请输入电子邮箱')">
                                <template v-if="activeTab == 0" #label>
                                    <div class="area-code text-[11px] font-semibold flex items-center px-1">
                                        {{ selectedCode }}
                                        <!-- <van-icon name="arrow-down" class="px-1" /> -->
                                        <div class="w-[1px] h-[20px] ml-2 bg-white"></div>
                                    </div>
                                </template>
                            </van-field>
                            <van-field v-model.trim="registerForm.verificationCode" maxlength="6" type="text"
                                :placeholder="$t('验证码')"
                                v-if="activeTab == 1 ? system.disposition?.register_info?.is_verify_email_code == 1 : system.disposition?.register_info?.is_verify_phone_code == 1">
                                <template #button>
                                    <div @click="sendVerificationCode"
                                        :class="isCounting ? ' cursor-not-allowed' : 'cursor-pointer'"
                                        class="light_txt text-[11px] font-semibold">{{ snedBtnText }}
                                    </div>
                                </template>
                            </van-field>
                            <van-field v-model.trim="registerForm.password" :maxlength="20" type="password"
                                :placeholder="$t('请输入密码')" />
                            <van-field v-model.trim="registerForm.repassword" :maxlength="20" type="password"
                                :placeholder="$t('请再次输入密码')"
                                v-if="system.disposition?.register_info?.is_repeat_password == 1" />
                            <van-field v-model.trim="registerForm.secupaypwd" :maxlength="20" type="password"
                                :placeholder="$t('请输入安全密码')" />
                            <van-field v-model.trim="registerForm.invite_code" :maxlength="10" :placeholder="$t('请输入邀请码')"
                                v-if="route.query.type == 2" />
                            <van-field v-model="registerForm.url" :maxlength="50"
                                :placeholder="route.query.type == 1 ? $t('请填写您的网站URL') : $t('请填写您的VAT号码')" v-else />
                        </van-cell-group>

                    </van-form>
                    <van-button class="w-full login_Btn mt-[8px]" @click="onSubmitRegisterForm">
                        <span class="text-[15px]">{{ route.query.type != 2 ? $t('提交审核') : $t('注册') }}</span>
                    </van-button>
                    <div class="flex justify-center pt-4 text-[11px] font-semibold">
                        <div @click="router.push('/login?type=' + route.query.type)" v-if="route.query.type == 2">
                            <span>{{ $t('已有账号？') }}</span>
                            <span class="text-[#FF5B01] ">{{ $t('登录') }}</span>
                        </div>
                        <span class="text-[#FF5B01] " v-else>{{ $t('一旦通过审核，我们会与您联系') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class=" absolute bottom-5 text-[11px] font-semibold text-white w-full text-center" @click="router.push('/choosetype')">
            {{ $t('切换身份') }}
        </div>
    </div>
</template>

<script setup>
import Tabs from "@/components/Tabs.vue"
import { ref, reactive, computed, onMounted } from "vue";
import { showToast } from 'vant';
import { AuthService } from '@/api/home'
import { useUserStore } from "@/store/userinfo";
import { useRouter, useRoute } from "vue-router";
import { useI18n } from 'vue-i18n';
import { useSystemStore } from "../store/system";

const { t } = useI18n();
const store = useUserStore();
const system = useSystemStore();
const router = useRouter()
const route = useRoute();
const isRemeberPassword = ref(false)
const isAgree = ref(true)
let registerForm = reactive(
    {
        username: "",
        password: "",
        secupaypwd: "",
        verificationCode: "",
        invite_code: "",
        repassword: "",
        pid: "",
        url: "",
        click_id: "",
    }
)
const onTabChange = (index) => {
    activeTab.value = index
    registerForm = reactive(
    {
        username: "",
        password: "",
        secupaypwd: "",
        verificationCode: "",
        invite_code: "",
        repassword: "",
        pid: "",
        url: "",
        click_id: "",
    }
)

}
const tabList = ref([])
const activeTab = ref(0)
const fetchRgMethon = () => {
    switch (system.disposition.register_info.register_account_status) {
        case "0":
            tabList.value = ['手机', '电子邮箱']
            break;
        case "1":
            tabList.value = []
            activeTab.value = 1
            break;
        case "2":
            tabList.value = []

            break
    }
}

/* const allAreaCodes = ["+52", "+55", "+86", "+1", "+44", "+33", "+91"] */
const allAreaCodes = ["+52"]
const selectedCode = ref("")
const searchInput = ref("")

const isCounting = ref(false)
const countdown = ref(60)
const snedBtnText = computed(() => {
    return isCounting.value ? `${countdown.value}s${t('后重试')}` : t('发送验证码');
})
const sendVerificationCode = async () => {
    if (isCounting.value) return;
    if (!registerForm.username) {
        return showToast(activeTab.value == 1 ? t('请输入电子邮箱') : t('请输入手机号'))
    }
    if (activeTab.value == 1) {
        await AuthService.sendEmail({ email: registerForm.username });
    } else {
        if (!validateNumber(registerForm.username)) {
            return showToast(t("请输入10-11位号码"));
        };
        await AuthService.sendSms({ mobile: selectedCode.value + registerForm.username });
    }
    showToast(t("验证码已发送"))
    startCountdown();
}
function validateNumber(input) {
    if (!/^[1-9]\d{9,10}$/.test(input)) {
        return false;
    }
    const regex = /^\d{10,11}$/; // 10-11位纯数字
    return regex.test(input);
}
const startCountdown = () => {
    isCounting.value = true
    countdown.value = 60
    const timer = setInterval(() => {
        countdown.value -= 1;
        if (countdown.value <= 0) {
            clearInterval(timer); // 清除定时器
            isCounting.value = false; // 恢复到可点击状态
        }
    }, 1000);
}

const areaCodesList = computed(() => {
    return allAreaCodes.filter(item => item.toLowerCase().includes(searchInput.value.toLowerCase()));
})
const showPicker = ref(false)

const onConfirm = (value) => {
    selectedCode.value = value
    showPicker.value = false
}


const onSubmitRegisterForm = async () => {

    if (!isAgree.value && system.disposition?.site_info?.is_show_user_protocol != 0) {
        return showToast(t("请勾选协议"))
    }
    if (!registerForm.username) {
        return showToast(activeTab.value == 1 ? t('请输入电子邮箱') : t('请输入手机号'))
    } else {
        if (activeTab.value != 1) {
            if (!validateNumber(registerForm.username)) {
                return showToast(t("请输入10-11位号码"));
            };
        }
    }
    if (!registerForm.password) {
        return showToast(t('请输入密码'))
    }
    if (!registerForm.secupaypwd) {
        return showToast(t('请输入安全密码'))
    }
    if (!registerForm.repassword && system.disposition?.register_info?.is_repeat_password == 1) {
        return showToast(t('请再次输入密码'))
    }
    if (!registerForm.verificationCode && (activeTab.value == 1 ? system.disposition?.register_info?.is_verify_email_code == 1 : system.disposition?.register_info?.is_verify_phone_code == 1)) {
        return showToast(t('请输入验证码'))
    }
    if (route.query.type != 2) {
        if (!registerForm.url) {
            return showToast(route.query.type == 1 ? t('请填写您的网站URL') : t('请填写您的VAT号码'))
        }
        setTimeout(() => {
            showToast(t("已提交，等待审核"))
        }, 1000)
        return
    }
    if (!registerForm.invite_code && system.disposition?.register_info?.invitation_code_require == 1) {
        return showToast(t('请输入邀请码'))
    }
    let params = {
        register_type: activeTab.value == 0 ? "mobile" : "email",
        password: registerForm.password,
        paypwd: registerForm.secupaypwd,
        verificationCode: registerForm.verificationCode,
        invite_code: registerForm.invite_code,
        repassword: registerForm.repassword,
        pid: registerForm.pid,
        click_id: registerForm.click_id,
    }
    if (activeTab.value == 0) {
        params['phone_code'] = selectedCode.value
        params['mobile'] = registerForm.username
    } else {
        params['email'] = registerForm.username
    }
    const res = await AuthService.register(params);
    showToast(t("注册成功"))
    store.setuserInfo(res);
    // 清空缓存
    store.clearpipassword()
    store.clearencode();
    store.clearprecisionpassword()
    router.replace('/')
}
onMounted(() => {
    const popu = localStorage.getItem("popularizar")
    if (popu) {
        try {
            const pop = JSON.parse(popu);
            registerForm.invite_code = pop.ic
            registerForm.pid = pop.pid
            registerForm.click_id = pop.click_id
        } catch (error) {

        }
    }


    if (route.query.ic) {
        registerForm.invite_code = route.query.ic
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

.login_Btn {
    background: linear-gradient( 89deg, #FF4901 0%, #FF6F01 100%);
    border-radius: 10px;
    border: none;
    padding: 20px;
}

:deep(.van-cell) {
    padding: 5px !important;
    border-radius: 8px !important
}

:deep(.van-field__control::placeholder) {
    color: #fff !important;
}

:deep(.van-field__button) {
    border-left: 2px solid #fff;
}
</style>