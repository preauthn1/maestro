<template>
    <div class="profile_container pb-[70px] mt-[20px]">
        <div class="mine pb-[20px]">
            <GeneralNavBar />
            <div class="profile_head pt-[30px]">
                <div class="mb-6 flex mt-[24px] ml-[16px] mr-[16px] justify-between items-center">
                    <div class="w-[60px] h-[60px] rounded-full bg-gray-300 overflow-hidden">
                        <img :src="store.userInfo?.user_info?.avatar" alt="" />
                    </div>
                    <div class="flex-1 flex justify-between items-center">
                        <div class="ml-[10px]" v-if="store.userInfo?.user_info?.invite_code">
                            <div class="flex items-center ">
                                <span class="text-[11px] font-bold pr-[10px]">{{ showUserName() }}</span>
                                <img :src="showName ? $getImageUrl('eye-o.png') : $getImageUrl('closed-eye.png')"
                                    class="w-[16px] h-[16px]" @click="showName = !showName" />
                                <!-- <van-icon @click="showName = !showName" class="ml-[10px]"
                                :name="showName ? $getImageUrl('eye-o.png'): $getImageUrl('closed-eye.png')" size="20" /> -->
                            </div>
                            <div class="flex items-center">
                                <span class="text-[11px] font-bold">{{ $t("邀请码") }}：{{
                                    showInvCode() }}</span>
                                <van-image :src="$getImageUrl('icon_copy_white.png')"
                                    @click="$copy(store.userInfo?.user_info?.invite_code, t)"
                                    class="w-[15px] h-[15px] ml-[10px]" object-fit="contain">

                                </van-image>
                            </div>

                        </div>
                        <!-- <div class="flex-0 w-[35px] h-auto" @click="router.push('/dailyattendance')">
                        <img :src="$getImageUrl('gift.png')" class="w-[24px] h-[24px]" />
                    </div> -->
                    </div>
                </div>
                <div class="gradient-border-box-fixed mt-[20px] rounded-[5px] px-[30px] mx-[11px] pb-[10px]">
                    <div class="">
                        <div class="mt-[16px] flex flex-col items-center" @click="router.push('/eWallet')">
                            <div class="mt-2 text-lg text-[13px] font-medium flex items-center">
                                <span class="pr-[10px]">{{ $t("账户余额") }}</span>
                                <van-icon name="arrow" size="15" />
                            </div><!-- {{ store?.userInfo?.user_info?.user_money < 0
                            ? 0 : store?.userInfo?.user_info?.user_money }} -->
                            <div class="text-[18px] font-bold text-[#15CF00]">{{$sysCurrency}} {{store?.userInfo?.user_info?.user_money_value < 0 ? 0 : store?.userInfo?.user_info?.user_money_value}}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 pb-[15px]">
                        <div class="mt-[12px] font-semibold pr-[20px]">
                            <div class="mt-2 text-[13px]  font-medium">{{ $t("总收入") }}
                            </div>
                            <div class="text-[15px]  font-semibold text-[#15CF00]">
                                {{ store?.userInfo?.user_info?.total_income }}</div>
                        </div>
                        <div class="mt-[12px] font-semibold border-l border-[#FF5001] pl-[20px]">
                            <div class="mt-2 text-[13px]  font-medium">{{ $t("佣金总收入") }}
                            </div>
                            <div class="text-[15px]  font-semibold text-[#15CF00]">
                                {{ store?.userInfo?.user_info?.total_brokerage_income }}</div>
                        </div>
                    </div>
                    <!-- <div class="w-[80%] h-[1px] bg-[#fff] my-[20px] mx-auto"></div> -->
                    <div class="mt-[20px]">
                        <div class="grid grid-cols-2 gap-[20px] pb-[15px]">
                            <span class="text-[12px] font-medium ">{{ $t('可信指数') }}</span>
                            <div class="text-[12px] font-medium text-[#FFFB00] flex items-center">
                                {{ store.userInfo?.user_info?.credit_score + ' ' + $t('分') }}
                                <span class="pl-[8px]" @click="fetchrule">
                                    <svg t="1745984692674" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" p-id="2768" width="13" height="13">
                                        <path
                                            d="M512 4.12672c280.49408 0 507.87328 227.3792 507.87328 507.87328 0 280.49408-227.3792 507.87328-507.87328 507.87328C231.50592 1019.87328 4.12672 792.49408 4.12672 512 4.12672 231.50592 231.50592 4.12672 512 4.12672zM512 685.96736c-42.47552 0-76.91264 34.42688-76.91264 76.91264 0 42.47552 34.43712 76.91264 76.91264 76.91264 42.47552 0 76.91264-34.43712 76.91264-76.91264C588.91264 720.39424 554.47552 685.96736 512 685.96736zM509.78816 625.83808c36.58752 0 66.24256-29.66528 66.24256-66.24256l0-309.1456c0-36.58752-29.65504-66.24256-66.24256-66.24256-36.58752 0-66.24256 29.66528-66.24256 66.24256l0 309.1456C443.5456 596.18304 473.20064 625.83808 509.78816 625.83808z"
                                            fill="#e6e6e6" p-id="2769"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-[20px] pb-[15px]">
                            <div class=" text-[12px] font-medium">{{ $t("AI预支金额") }}</div>
                            <div class="text-[12px] font-medium  text-[#FFFB00]">
                                {{ $sysCurrency }} {{ store?.userInfo?.user_info?.user_money_value < 0 ?
                                        -(store?.userInfo?.user_info?.user_money_value) : 0 }}
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-[20px] pb-[15px]">
                        <div class=" text-[12px] font-medium ">{{ $t("待归还金额") }}</div>
                        <div class="text-[12px] font-medium  text-[#FFFB00]">
                           
                            {{ $sysCurrency }} {{ store?.userInfo?.user_info?.user_money_value < 0 ?
                                        -(store?.userInfo?.user_info?.user_money_value) : 0 }}
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-[15px] ">
                        <div class=" text-[12px] font-medium pr-[10px] w-[120px] shrink-0">{{ $t("个人交易量") }}</div>
                        <div class=" text-[12px] font-medium flex w-full items-center gap-[2px] flex-1">
                            <van-progress class="w-[90%]"
                                :percentage="store.userInfo?.user_info?.credit_score_detail?.trade || 0"
                                track-color="#222528" stroke-width="6" color="#00E936" pivot-text="" />
                            <span>{{ store.userInfo?.user_info?.credit_score_detail?.trade }}%</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-[15px]">
                        <div class=" text-[12px] font-medium  pr-[10px] w-[120px] shrink-0">{{ $t("团队活跃人数") }}</div>
                        <div class=" text-[12px] font-medium  w-full flex items-center">
                            <van-progress class="w-[90%]"
                                :percentage="store.userInfo?.user_info?.credit_score_detail?.active || 0"
                                track-color="#222528" stroke-width="6" color="#F03C00" pivot-text="" />
                            <span>{{ store.userInfo?.user_info?.credit_score_detail?.active }}%</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-[15px]">
                        <div class=" text-[12px] font-medium pr-[10px] w-[120px] shrink-0">{{ $t("新增人数") }}</div>
                        <div class=" text-[12px] font-medium w-full flex items-center flex-1">
                            <van-progress class="w-[90%]"
                                :percentage="store.userInfo?.user_info?.credit_score_detail?.new || 0"
                                track-color="#222528" stroke-width="6" color="#F03C00" pivot-text="" />
                            <span>{{ store.userInfo?.user_info?.credit_score_detail?.new }}%</span>
                        </div>
                    </div>
                    <div class="text-[10px] font-medium text-white py-[5px]">
                        {{ $t('是否需要归还取决于您的可信指数，点击「!」查看预支额度说明。') }}
                    </div>
                </div>
            </div>
        </div>
        <div
            class="gradient-border-box-fixed-leiji rounded-[10px] mt-[20px] mx-[11px] mb-[10px] flex flex-col justify-around">
            <div class="flex justify-around py-[24px]">
                <div class="flex flex-col items-center justify-center ">
                    <div class="text-[12px] font-bold text-white">{{ $t("累计充值") }}</div>
                    <div class="text-[12px] font-bold text-[#15CF00]  mt-[10px]">{{
                        store?.userInfo?.user_info?.recharge_money
                    }}</div>
                </div>
                <!-- <div class="w-[1px] mt-[10px] mb-[10px] bg-[#FFFFFF24]"></div> -->
                <div class="flex flex-col items-center justify-center">
                    <div class="text-[12px] font-bold text-white">{{ $t("累计提现") }}</div>
                    <div class="text-[12px] text-[#15CF00] font-bold  mt-[10px]">{{
                        store?.userInfo?.user_info?.withdrawal_amount }}
                    </div>
                </div>
            </div>
            <div class="mx-[11px]">
                <div class="w-full h-[1px] bg-[#FF6F01]"></div>
            </div>
            <div class="mt-[20px] mb-[10px] flex justify-around">
                <div class="flex flex-col items-center justify-center">
                    <div class="text-[12px] font-bold text-white">{{ $t("团队总人数") }}</div>
                    <div class="text-[12px] text-[#15CF00] font-bold  mt-[10px]">{{
                        store?.userInfo?.user_info?.team_num }}
                        {{
                            $t("人")
                        }}</div>
                </div>
                <!-- <div class="w-[1px] mt-[10px] mb-[10px] bg-[#FFFFFF24]"></div> -->
                <div class="flex flex-col items-center justify-center">
                    <div class="text-[12px] font-bold text-white">{{ $t("今日新增人数") }}</div>
                    <div class="text-[12px] text-[#15CF00] font-bold mt-[10px]">
                        {{ store?.userInfo?.user_info?.today_team_num }} {{ $t("人") }}
                    </div>
                </div>
            </div>
        </div>
        <div class="m-[11px]">
            <div class="yue-content relative flex flex-col" @click="router.push('/yuebao')">
                <div class="my-6 px-[60px]">
                    <div class="flex items-center">
                        <!-- <img :src="$getImageUrl('head.png')" class="w-[40px] h-auto" alt=""> -->
                        <div class="text-[18px] items-center pl-2 font-medium">{{ $t('AI智能交易') }}</div>
                    </div>
                    <div class="text-[13px] mt-1">{{ $t("通过AI智能管理自动赚取收益") }}</div>
                </div>
                <!-- <img :src="$getImageUrl('robot.png')" class=" absolute w-[90px] h-auto right-0 top-[-14px]"
                                alt=""> -->
            </div>
        </div>
        <div class="px-[11px] w-full">
           
        </div>

        <div class="mt-[20px] mx-[11px] gradient-border-box-fixed-leiji relative z-[1] p-[21px]">
            <div class=" relative z-[2]">
                <div class="w-full py-2 mb-[20px]  flex justify-between items-center" @click="changePage('/bankcard')">
                    <div class="flex items-center">
                        <img :src="$getImageUrl('icon_bankcard.png')" class="w-[20px] h-auto object-contain" alt="">
                        <span class="text-[13px] pl-4">{{ $t('银行卡') }}</span>
                    </div>
                    <van-icon name="arrow" size="20" />
                </div>
                <div class="w-full py-2 mb-[20px] flex justify-between items-center"
                    @click="changePage('/loginPassword')">
                    <div class="flex items-center">
                        <img :src="$getImageUrl('icon_password.png')" class="w-[20px] h-auto object-contain" alt="">
                        <span class="text-[13px] pl-4">{{ $t('登录密码') }}</span>
                    </div>
                    <van-icon name="arrow" size="20" />
                </div>
                <div class="w-full py-2 mb-[20px] flex justify-between items-center"
                    @click="changePage('/securePassword')">
                    <div class="flex items-center">
                        <img :src="$getImageUrl('icon_secure.png')" class="w-[20px] h-auto object-contain" alt="">
                        <span class="text-[13px] pl-4">{{ $t('安全密码') }}</span>
                    </div>
                    <van-icon name="arrow" size="20" />
                </div>
                <div class="w-full py-2 mb-[20px] flex justify-between items-center"
                    @click="changePage('/investmentrecords')">
                    <div class="flex items-center">
                        <img :src="$getImageUrl('icon_record.png')" class="w-[20px] h-auto object-contain" alt="">
                        <span class="text-[13px] pl-4">{{ $t('记录') }}</span>
                    </div>
                    <van-icon name="arrow" size="20" />
                </div>
                <div class="w-full py-2 mb-[20px] flex justify-between items-center"
                    @click="changePage('/helpCenter?type=2')">
                    <div class="flex items-center">
                        <img :src="$getImageUrl('icon_help.png')" class="w-[20px] h-auto object-contain" alt="">
                        <span class="text-[13px] pl-4">{{ $t('帮助中心') }}</span>
                    </div>
                    <van-icon name="arrow" size="20" />
                </div>
                <div class="w-full py-2 mb-[20px] flex justify-between items-center"
                    @click="changePage('/helpCenter?type=1')">
                    <div class="flex items-center">
                        <img :src="$getImageUrl('about.png')" class="w-[20px] h-auto object-contain" alt="">
                        <span class="text-[13px] pl-4">{{ $t('关于我们') }}</span>
                    </div>
                    <van-icon name="arrow" size="20" />
                </div>
                <div class="w-full py-2 mb-[20px] flex justify-between items-center" @click="changePage('/notice')">
                    <div class="flex items-center">
                        <img :src="$getImageUrl('icon_notice.png')" class="w-[20px] h-auto object-contain" alt="">
                        <span class="text-[13px] pl-4">{{ $t('通知') }}</span>
                    </div>
                    <van-icon name="arrow" size="20" />
                </div>
                <div class="w-full py-2 mb-[20px] flex justify-between items-center" @click="showInstall = true"
                    v-if="!isMobil">
                    <div class="flex items-center">
                        <img :src="$getImageUrl('icon_app_down.png')" class="w-[20px] h-auto object-contain" alt="">
                        <span class="text-[13px] pl-4">{{ $t('下载') }}</span>
                    </div>
                    <van-icon name="arrow" size="20" />
                </div>
                <!-- <van-cell :title="$t('实名认证')" :icon="$getImageUrl('icon_authentication.png')"
                @click="changePage('/authentication')" center icon-prefix="profile-menu-icon" is-link /> -->

            </div>

        </div>
        <div class=" mt-[20px] mx-[11px] pb-[42px]">
            <van-button round block class=" border-none rounded-[6px] "
                style="background:linear-gradient( 89deg, #FF4901 0%, #FF7801 100%);height: 44px;" @click="logout">
                <span class="  font-bold">{{ $t("退出账户") }}</span>
            </van-button>
        </div>
        <van-popup v-model:show="showCenter" class="px-[11px] w-full bg-[#00000000]">
            <div class="bg-white rounded-[10px] border border-[#FF5001] ">
                <div class="flex justify-center relative ">
                    <span class=" rounded-b-[18px] py-[7px] px-[46px] text-[17px] font-semibold text-[#FF5001]">{{
                        $t('规则说明') }}</span>
                </div>
                <div class="m-[10px] p-[10px] min-h-[50px] border border-[#FF5001] rounded-[10px] notice_rich">
                    <div v-html="rules?.content" class="h-[400px] py-3 overflow-auto regras"></div>
                </div>
                <div class="flex justify-center px-[30px]">
                    <van-button type="primary" size="large" @click="showCenter = false" class="my-[10px]">
                        <span class="text-white text-[14px] font-semibold">{{ $t("确认") }}</span>
                    </van-button>
                </div>
            </div>
        </van-popup>
        <!--  <van-popup v-model:show="isShow" position="bottom" class="pop">
            <div class="pop-content">
                <div class="content-wapper">
                    <img :src="system.disposition?.site_info?.site_logo" class="logo">
                    <span class="desc">{{ $t("我们希望将本站添加到您的主屏幕，以获取更好的体验") }}。</span>
                </div>
                <div class="btn-wapper">
                    <span class="cancel" @click="isShow = false">{{ $t("取消") }}</span>
                    <van-button class=" ml-[20px] w-[80px] h-[30px]" round block type="primary" @click="inStall">{{
                        $t("继续")
                        }}</van-button>
                </div>
            </div>
        </van-popup> -->

        <Install v-model:showCenter="showInstall" @on-close="showInstall = false" />
    </div>
</template>

<script setup>
import { useUserStore } from '../store/userinfo';
import GeneralNavBar from "@/components/GeneralNavBar.vue"
import { useRouter } from "vue-router";
import { onMounted, ref } from "vue";
import { useSystemStore } from "../store/system";
import { useI18n } from 'vue-i18n';
import { AuthService } from '@/api/home';
import Install from '@/components/install.vue';
const { t } = useI18n()
const showPrompt = ref(false);
const system = useSystemStore();
const showCenter = ref(false)
const store = useUserStore();
const showInstall = ref(false);
const router = useRouter()

const showName = ref(false);
function showUserName() {
    let userNameString = ref('');
    userNameString = store.userInfo?.user_info?.user_name;
    if (!showName.value) {
        if (isEmail(userNameString)) {
            userNameString = userNameString.replace(/^(.).*?(@.*)$/, (match, firstChar, domain) => {
                return firstChar + "****" + domain;
            });
        } else {
            userNameString = `${store.userInfo?.user_info?.user_name.slice(0, 4)}****${store.userInfo?.user_info?.user_name.slice(-3)}`;
        }
    }
    return userNameString;
}
const showInvCode = () => {
    let codeString = ref('');
    codeString = store.userInfo?.user_info?.invite_code;
    codeString = `${codeString.slice(0, 2)}****${codeString.slice(-2)}`;
    return codeString;
}
function isEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}


const logout = () => {
    store.clearUserInfo();
    router.push('/choosetype')
}

const changePage = (path) => {
    router.push(path)
}
const isShow = ref(false)
/* 
const addScreen = () => {
    isShow.value = true
} */
const colorA = {
    "0%": "#F03C00",
    "50%": "#FFBF00",
}

const userAgent = navigator.userAgent.toLowerCase();
const deviceType = ref('');
if (window.navigator.standalone) {
    deviceType.value = 'IPHONE';
} else {
    const userAgent1 = navigator.userAgent || navigator.vendor;
    if (/iPhone|iPad|iPod/.test(userAgent1) && !/Safari/.test(userAgent1)) {
        deviceType.value = 'IPHONE';
    } else if (/iPhone|iPad|iPod/.test(userAgent) && window.innerWidth === screen.width &&
        window.innerHeight === screen.height) {
        deviceType.value = 'IPHONE';
    } else {
        if (userAgent.includes("bl999.andriod")) {
            deviceType.value = 'ANDROID';
        } else {
            deviceType.value = 'WINDOWS';
        }
    }
}

//处理带归还金额
function formatCurrency(value) {
    // 匹配 R$ 后可能带空格的负号
    if (/-/.test(value)) {
        return value.replace(/(R\$)\s*-/, "$1 ").trim(); // 去掉负号
    } else {
        return "R$ 0";
    }
}
const isMobil = ref(deviceType.value != 'WINDOWS');
const rules = ref()
const fetchrule = async () => {
    if (rules.value) {
        showCenter.value = true
        return;
    }
    const res = await AuthService.article_details({ type: "14" })
    rules.value = res
    showCenter.value = true
}
onMounted(async () => {
    await store.getUserInfo()
    isShow.value = true
})

</script>

<style lang="less" scoped>
// .profile_head {
//     width: 100%;
//     // height: 334px;
//     background: url("@/assets/images/mine_bg.png") no-repeat;
//     background-size: 100% 100%;
//     padding-top: 60px;
// }

:deep(.notice_rich) {
    span {
        color: black !important;
    }

    p {
        color: black !important;
    }

    strong {
        color: black !important;
    }
}

.svg-wrap {
    display: inline-block;
    /* 让宽度随需要而定 */
    width: 100%;
    /* 或者你想用 100% / max-width 都行 */
    height: auto;
    /* 由 SVG 内部撑开 */
}

.profile-background-linear {

    background: linear-gradient(180deg, #16187A, #1333DA);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;

}

.yue-content {
    width: 100%;
    height: auto;
    background: url("@/assets/images/icon_yue_background.png") no-repeat center;
    background-size: 100%;
}




::v-deep(.van-cell__left-icon) {
    margin-right: 10px;
}

::v-deep(.profile-menu-icon) {
    width: 20px;
    height: 20px;

    img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
    }

}

::v-deep(.van-cell) {
    background-color: transparent !important;

    &::after {
        content: none;
    }
}

.wallet-type-backview {
    background-color: #15131E;
    border-radius: 26px 26px 0 0;
}

.service-line-color {
    -van-cell-border-color: #FFFFFF15;
}

.pop-content {
    padding: 12px;
}

.logo {
    width: auto;
    height: 54px;
    border-radius: 12px;
    object-fit: cover;
    margin-right: 12px;
    flex-shrink: 0;
}

.content-wapper {
    display: flex;
}

.desc {
    font-size: 14px;
    line-height: 1.5;
    margin-top: 4px;
}

// .hello{
//     margin-top: 20px;
//     width:200px;
//     background: #ffffff;
//     height: 60px;
//     border: 5px solid;
//     border-radius: 20px;
//     border-image: linear-gradient(270deg, rgba(255, 80, 1, 1), rgba(255, 162, 1, 1), rgba(255, 80, 1, 1)) 1 1;
// }
.btn-wapper {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-top: 2px;
}

.btn {
    margin-left: 29px;
    min-width: 89px;
}

.cancel {
    color: var(--text-color1);
    font-size: 14px;
}

.popup-background {
    background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    box-shadow: 0px 1px 4px 0px rgba(0, 0, 0, 0.15);
    border-radius: 18px;
}

.gradient-border-box-fixed {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background-color: rgba(255, 255, 255, 0.0);
    /* 白色，60% 透明度 */
    border-radius: 10px;
    /* **重要：保持和伪元素一致的圆角** */
    overflow: hidden;
    /* 确保内容不会溢出圆角 */
    /* Optional: 添加一个毛玻璃效果 (现代浏览器) */
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    /* Safari 兼容 */
}

:deep(.van-button--normal) {
    padding: 16px;
}

/* 创建渐变边框的伪元素 */
.gradient-border-box-fixed::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    /* 渐变背景作为边框 */
    background: linear-gradient(to right, #FF5A01, #FFB700, #FF5A01);
    /* 你的渐变颜色 */
    border-radius: 10px;
    /* **重要：与父元素保持一致的圆角** */
    z-index: -1;
    /* 放在主 div 的后面 */
    /* 让伪元素比主 div 稍微大一点，形成边框效果 */
    padding: 1px;
    /* 调整此值来控制边框的粗细 */
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    /* 用于裁剪内部 */
    mask-composite: exclude;
    /* 排除掉中间的部分，只留下边框 */
    -webkit-mask-composite: exclude;
    /* Safari 兼容 */
}

.gradient-border-box-fixed-leiji {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background: rgba(80, 37, 6, 0.35);
    /* 白色，60% 透明度 */
    border-radius: 10px;
    /* **重要：保持和伪元素一致的圆角** */
    overflow: hidden;
    /* 确保内容不会溢出圆角 */
    /* Optional: 添加一个毛玻璃效果 (现代浏览器) */
    filter: blur(0.2px);
    -webkit-backdrop-filter: blur(5px);
    /* Safari 兼容 */
}

/* 创建渐变边框的伪元素 */
.gradient-border-box-fixed-leiji::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    /* 渐变背景作为边框 */
    background: linear-gradient(to right, #FF5A01, #FFB700, #FF5A01);
    /* 你的渐变颜色 */
    border-radius: 10px;
    /* **重要：与父元素保持一致的圆角** */
    z-index: -1;
    /* 放在主 div 的后面 */
    /* 让伪元素比主 div 稍微大一点，形成边框效果 */
    padding: 1px;
    /* 调整此值来控制边框的粗细 */
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    /* 用于裁剪内部 */
    mask-composite: exclude;
    /* 排除掉中间的部分，只留下边框 */
    -webkit-mask-composite: exclude;
    /* Safari 兼容 */
}
</style>