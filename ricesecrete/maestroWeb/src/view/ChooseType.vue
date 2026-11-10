<template>
    <div class="logintype">
        <div class="flex justify-center pt-[10px] items-center relative z-[1] top-[30px]">
            <img :src="system.disposition?.site_info?.site_logo" class="px-[60px] h-[70px] object-cover" alt="">
        </div>
        <div class="flex justify-center text-[11px] relative z-[1] top-[70px] font-semibold">{{ $t('欢迎来到MAESTRO数字广告平台') }} </div>
        <div class=" relative z-[1] top-[100px]">
            <div class="pt-[10px]  pb-[20px] px-[40px] relative z-[2]">
                <div class="content px-[20px]">
                    <div class="text-white font-semibold pt-[28px] pb-[13px] text-[13px] w-full text-center">{{ $t('请选择您的身份以继续') }}</div>
                    <div class=" flex flex-col">
                        <div class="px-[8px] text-[11px] py-[7.5px] mb-[2.5px] h-[60px] relative" v-for="(item, index) in typeData"
                            @click="chooseType = index + 1">
                            <div class="w-full h-full rounded-[10px] flex relative justify-center items-center"
                                :class="item.color">
                                <div class="animateType" v-if="chooseType == index + 1"></div>
                                <span class="text-[13px] font-semibold">{{ item.name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class=" mx-[7px] mt-[50px] h-[50px] rounded-[20px] flex justify-center items-center login-btn-background"
                        @click="loginpage">
                        <span class="text-[13px] font-semibold">{{ $t('登录') }}</span>
                    </div>
                    <div class="flex py-[20px] justify-center text-[11px] font-semibold" @click="register">
                        <div>{{ $t('没有账号？') }}</div>
                        <div class="text-[#FF5B01]">{{ $t('注册') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" absolute bottom-[14px] text-[13px] font-semibold left-0 w-full flex justify-center">
            <!-- {{ $t("欢迎来到MAESTRO数字广告平台") }} -->
            <!-- v-if="system.disposition?.site_info?.is_show_user_protocol != 0">
            <van-checkbox v-model="isAgree" icon-size="19px" checked-color="#F9EF12">
                {{ $t("同意我们的") }}
            </van-checkbox>
            <router-link to="/agreement" class="text-[#F9EF12]">{{ $t("《用户协议暨隐私政策》") }}</router-link> -->
        </div>
    </div>
</template>

<script setup>
import { showToast } from 'vant'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n';
import { useSystemStore } from "../store/system";
const system = useSystemStore();
const { t } = useI18n();
const router = useRouter()
const isAgree = ref(true)
const typeData = ref([
    { name: t('广告发行商'), color: 'one-btn-background' },
    { name: t('广告代理商'), color: 'two-btn-background' },
    { name: t('广告主'), color: 'there-btn-background' },
]);
const chooseType = ref()
const loginpage = () => {
    if (!chooseType.value) {
        return showToast(t("请选择您的身份以继续"))
    }
    if (!isAgree.value) {
        return showToast(t("请勾选协议"))
    }
    router.push(`/login?type=${chooseType.value}`)
}
const register = () => {
    if (!chooseType.value) {
        return showToast(t("请选择您的身份以继续"))
    }
    if (!isAgree.value) {
        return showToast(t("请勾选协议"))
    }
    router.push(`/register?type=${chooseType.value}`)
}
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

.one-btn-background {
    background: linear-gradient( 268deg, #FDB602 0%, #FD8C02 100%);
    box-shadow: 0px 1px 1px 0px rgba(15, 9, 13, 0.2);
    border-radius: 10px;
}

.two-btn-background {
    background: linear-gradient( 89deg, #00AF0E 0%, #4FEE5C 100%);
    box-shadow: 0px 1px 1px 0px rgba(15, 9, 13, 0.2);
    border-radius: 10px;
}

.there-btn-background {
    background: linear-gradient( 89deg, #0051BF 0%, #0077FF 100%);
    box-shadow: 0px 1px 1px 0px rgba(15, 9, 13, 0.2);
    border-radius: 10px;
}

.login-btn-background {
    background: linear-gradient( 89deg, #FF4901 0%, #FF6F01 100%);
    box-shadow: 0px 1px 2px 0px rgba(15, 9, 13, 0.1);
    border-radius: 10px;
}

.login_Btn {
    background: #006BE8 !important;
    border-radius: 10px;
    padding: 10px;
}
.animateType{
    animation: animate 3s ease-in-out infinite;
    background: url('../assets/images/Grouptype.png');
    width: 62px;
    height: 45px;
    background-size: 100% 100%;
    background-repeat: no-repeat;
    position: absolute;
    left: 0px;
}

@keyframes animate{
    0%{
        left: 0px;
       
    }
    100%{
        left: 74%;
        
    }
}
</style>