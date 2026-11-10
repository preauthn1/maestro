<template>
    <div>
        <div>
            <van-nav-bar fixed left-arrow z-index="60" :title="$t('抽奖转盘')" @click-left="router.go(-1)">
                <template #left>
                    <van-icon name="arrow-left" color="#FF5001" />
                </template>
                <template #title>
                    <span class="text-[11px] font-semibold">{{ $t('抽奖转盘') }}</span>
                </template>
            </van-nav-bar>
            <div class="h-[5.6rem]"></div>
            <div class=" px-[22px] pb-[10px] flex items-center justify-end">
                <span @click="toRuleDetail" class="text-[13px] font-bold">{{ $t('规则') }}</span>
                <div class="bg-[#fff] w-[2px] h-[14px] mx-[5px]"></div>
                <span @click="toDetails" class="text-[13px] font-bold">{{ $t('明细') }}</span>
            </div>
            <div class="  overflow-x-hidden">
                <div v-if="canHelp">
                    <div class="pt-[5px] pb-[30px] px-[30px]">
                        <div class="gradient-border-box-fixed-leiji">
                            <div class="px-[30px] pt-[5px] pb-[15px] flex flex-col items-center justify-between">
                                <span class="text-[#15CF00] text-[18px] font-bold">{{ $sysCurrency }} {{
                                    turntableInfo.amount }}
                                </span>
                                <div class="mt-[10] w-full">
                                    <div class="w-full flex justify-between pb-[4px]">
                                        <span class="text-[12px] font-medium">{{ $t('提现完成值') }}</span>
                                        <span class="text-[12px] font-medium">{{ turntableInfo.progress || 0 }}%</span>
                                    </div>
                                    <van-progress pivot-text="" stroke-width="12" color="#FF6F01"
                                        :percentage="turntableInfo.progress" />
                                </div>
                                <div class="w-full px-[30px] h-[34px] mt-[10px]">
                                    <div class="help-button-background h-[34px] flex items-center justify-center"
                                        @click="onPddReceive">
                                        <span class="text-[13px] font-semibold text-white">{{ canHelpDone ? $t('领取奖励') :
                                            $t('即将领取')
                                            }}</span>
                                    </div>
                                </div>
                                <div class="mt-[10px] text-center"
                                    :style="{ marginBottom: canHelpDone ? '20px' : '0px' }">
                                    <span v-if="!canHelpDone" class="text-[11px] font-semibold">{{ $t('离提现仅差') }}
                                        <span class="text-[#FFFC00]">{{ differenceStr }}%</span>，{{ $t('快去邀请好友助力提现！') }}
                                        （{{ $t('预计还需邀请') }}<span class="text-[#15CF00]">{{ turntableInfo.max_invite
                                        }}</span>{{ $t('人提现') }}）
                                    </span>
                                    <span v-else class="text-[11px] font-semibold">{{ $t('恭喜！您已完成提现任务') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex items-center justify-center pb-[60px]">
                        <div class="absolute w-full  aspect-square"></div>
                        <div class="z-20 flex justify-center wheel" :class="{ 'wheel-slow': !spinning }"
                            :style="{ transform: `rotate(${rotatePro}deg)` }">
                            <img class=" w-[98vw] h-[98vw] max-w-[425px] max-h-[425px] overflow-hidden object-cover"
                                :src="$getImageUrl('icon_help_wheel_pan.png')" alt="">
                            <div class="help-sector absolute flex bottom-0 flex-col items-center pt-[20px]"
                                style="top: 10%;" v-for="(prize, index) in helpData"
                                :style="{ transform: `rotate(${index * (360 / helpData.length) + 30}deg)` }">
                                <span class=" text-red-600 pt-[200px] text-[10px] text-center font-medium">{{
                                    prize.name }}</span>
                            </div>
                        </div>
                        <!-- <div class=" absolute bottom-0 w-full h-[60px] wheel-bottom-background">
                        </div> -->
                        <div class="absolute w-[26.66667vw] max-w-[113px] -translate-y-[10%] z-30"
                            :class="spinning ? ' cursor-not-allowed' : ' cursor-pointer'" @click="spinPro()">
                            <img :src="$getImageUrl(canHelpDone ? 'nozhizhen.png' : 'zhizhen.png')">
                        </div>
                        <div class=" absolute bottom-[-10px] w-[354px] h-auto">
                            <img :src="$getImageUrl('icon_help_wheel_base.png')" alt="">
                            <div
                                class=" text-center absolute bottom-[20%] left-[50%] transform -translate-x-1/2 -translate-y-1/2">
                                <span class="text-[13px] font-semibold">{{ $t("可用抽奖次數") }}：
                                    <span class="text-[#FFFB00] text-[13px] font-semibold">{{
                                        turntableInfo.turntable_count
                                        || 0 }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else>
                    <div class="relative flex items-center justify-center pb-[60px]">
                        <div class="absolute w-full  aspect-square"></div>
                        <div class="z-20 wheel relative" :class="{ 'wheel-slow': !spinning }"
                            :style="{ transform: `rotate(${rotate}deg)` }">
                            <img class=" w-[98vw]  max-w-[425px]  object-cover" :src="$getImageUrl('Group.png')" alt="">
                            <div class="sector absolute flex flex-col items-center justify-center" style="top: 10%;"
                                v-for="(prize, index) in turntableInfo.prizes"
                                :style="{ transform: `rotate(${index * (360 / turntableInfo.prizes.length) + 45}deg)` }">
                                <span class="">
                                    <span class="text-[#FF5001] text-[9px] font-semibold">{{ $sysCurrency }} {{
                                        prize.value }}</span>
                                </span>

                            </div>


                            <!-- <div class="circular-box" v-for="index in 20" :key="index"
                                :style="`transform: rotate(${360 / 20 * index}deg);`">
                                <div class="circular"></div>
                            </div> -->
                        </div>

                        <!-- <div class=" absolute bottom-0 w-full h-[60px] wheel-bottom-background">
                        </div> -->
                        <div class="absolute w-[26.66667vw] max-w-[113px] -translate-y-[10%] z-30"
                            :class="spinning ? ' cursor-not-allowed' : ' cursor-pointer'" @click="spin()">
                            <img :src="$getImageUrl('zhizhen.png')">
                        </div>
                        <div class=" absolute bottom-[-10px] w-[354px] h-auto">
                            <img :src="$getImageUrl('icon_wheel_base.png')" alt="">
                            <div
                                class=" text-center absolute bottom-[20%] left-[50%] transform -translate-x-1/2 -translate-y-1/2">
                                <span class="text-[13px] font-semibold">{{ $t("可用抽奖次數") }}：
                                    <span class="text-[#FFFB00] text-[13px] font-semibold">{{
                                        turntableInfo.turntable_count
                                        || 0 }}</span>
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="pt-[19px] px-[11px] pb-[16px] ">
                    <!-- <div class="relative">
                        <div class="bg-white rounded-[10px]">
                            <div class="pt-[16px] pl-[26px]">
                                <span class="text-[#FF5C01] text-[15px] font-bold">{{ $t('邀请好友助力') }}</span>
                            </div>
                            <div class="px-[16px] mt-[10px] flex flex-col">
                                <span class="text-black text-[10px] font-medium">{{ $t('邀请链接') }}：</span>
                                <span class="text-black text-[10px] flex items-center">{{
                                    store.userInfo?.user_info?.share_link }}<van-icon
                                        :name="$getImageUrl('icon_copy_black.png')" class="pl-[5px]"
                                        @click="$copy(store.userInfo?.user_info?.share_link, t)" /></span>
                            </div>
                            <div class="mt-[16px] flex justify-around pb-[16px]">
                                <div class="flex flex-col items-center" v-for="item in shareData"
                                    @click="share(item.name)">
                                    <img :src="item.img" class="w-[50px] h-[50px]" alt="">
                                    <span class="text-black text-[10px] font-bold">{{ item.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="flex justify-center items-center" v-if="canHelp">
                        <div class="">
                            <van-count-down :time="time" />
                        </div>
                        <div class="ml-[5px] text-[14px] text-[#FF5C02] font-bold ">{{ $t('后奖励失效') }}</div>
                    </div>

                    <div class="gradient-border-box-fixed-leiji mt-[23px]">
                        <div class="flex">
                            <span class="bg-[#FF5C02] py-[3px] px-[15px] rounded-tl-[10px] rounded-br-[15px]">{{
                                $t('邀请获奖榜') }}</span>
                        </div>
                        <div class="px-[24px] py-[15px]">
                            <van-swipe :autoplay="3000" :touchable="false" :show-indicators="false">
                                <van-swipe-item v-for="item in pddRecords">
                                    <div class="flex flex-col justify-around items-center">
                                        <span v-for="i in item" class="text-[#FFFFFF] text-[10px] text-left">{{ $t('用户')
                                            }}<span class="">{{ i.user_name }}</span>
                                            {{ $t('完成邀请任务 成功领取') }}
                                            <span class="text-[#15CF00] font-bold">{{ $sysCurrency }} {{ i.earnings
                                            }}!</span>
                                        </span>
                                    </div>
                                </van-swipe-item>
                            </van-swipe>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <van-popup v-model:show="showRule" class="px-[30px] w-full bg-[#00000000]">
            <div class="bg-white rounded-[10px] border border-[#FF5001] ">
                <div class="flex justify-center relative pt-[19px]">
                    <span class=" rounded-b-[18px] py-[7px] px-[46px] text-[17px] font-semibold text-[#FF5001]">{{
                        $t('规则说明') }}</span>
                    <div class=" absolute right-[12px] top-[12px]" @click="showRule = false">
                        <svg t="1751957349596" class="icon" viewBox="0 0 1024 1024" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" p-id="4615"
                            data-spm-anchor-id="a313x.search_index.0.i2.10e63a81n4IT1J" width="20" height="20">
                            <path
                                d="M512 466.944l233.472-233.472a31.744 31.744 0 0 1 45.056 45.056L557.056 512l233.472 233.472a31.744 31.744 0 0 1-45.056 45.056L512 557.056l-233.472 233.472a31.744 31.744 0 0 1-45.056-45.056L466.944 512 233.472 278.528a31.744 31.744 0 0 1 45.056-45.056z"
                                fill="#FF5001" p-id="4616" data-spm-anchor-id="a313x.search_index.0.i0.10e63a81n4IT1J"
                                class="selected"></path>
                        </svg>
                    </div>
                </div>
                <div class="m-[10px] p-[10px] min-h-[100px] border border-[#FF5001] rounded-[10px]">
                    <div v-html="content?.content" class="h-[400px] py-3 overflow-auto regras"></div>
                </div>
            </div>
        </van-popup>
        <van-popup v-model:show="showdetails" class="bg-white border border-[#FF5001] rounded-[10px] w-[90%] h-[447px]">
            <div class="flex justify-end px-[12px] pt-[12px]" @click="showdetails = false">
                <svg t="1751957349596" class="icon" viewBox="0 0 1024 1024" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" p-id="4615"
                    data-spm-anchor-id="a313x.search_index.0.i2.10e63a81n4IT1J" width="20" height="20">
                    <path
                        d="M512 466.944l233.472-233.472a31.744 31.744 0 0 1 45.056 45.056L557.056 512l233.472 233.472a31.744 31.744 0 0 1-45.056 45.056L512 557.056l-233.472 233.472a31.744 31.744 0 0 1-45.056-45.056L466.944 512 233.472 278.528a31.744 31.744 0 0 1 45.056-45.056z"
                        fill="#FF5001" p-id="4616" data-spm-anchor-id="a313x.search_index.0.i0.10e63a81n4IT1J"
                        class="selected">
                    </path>
                </svg>
            </div>
            <div class="h-full">
                <div class="pt-[10px] pb-[10px]">
                    <van-tabs v-model:active="active" title-active-color="#FF5C02" title-inactive-color="#FFC3A2">
                        <van-tab :title="$t('抽奖记录')">
                            <template #title>
                                <span class="text-[12px] font-semibold"
                                    :style="{ color: active == 0 ? '#FF5C02' : '#FFC3A2' }">{{ $t('抽奖记录') }}</span>
                            </template>
                            <div class="px-[20px]">
                                <div class="turnable-record-item flex justify-between items-center mb-[8px]"
                                    v-for="item in drawRecord">
                                    <div class="ml-[16px] flex flex-col mt-[8px] mb-[8px]">
                                        <span class="text-[13px] font-medium" v-if="item.type != 2">{{ $t('奖励')
                                            }}</span>
                                        <span class="text-[13px] font-medium" v-else>{{ $t('提现完成值') }}</span>
                                        <!--  -->
                                        <span class="text-[10px] font-medium">{{ item.create_time }}</span>
                                    </div>
                                    <div class="mr-[16px] flex justify-end">
                                        <span class="text-[15px] font-bold text-[#FFFB00] text-right"
                                            v-if="item.type != 2">
                                            {{ $sysCurrency }} +{{ item.value }}</span>
                                        <span class="text-[15px] font-bold text-[#FFFB00] text-right" v-else>+{{
                                            item.value }}%</span>
                                    </div>
                                </div>
                            </div>
                        </van-tab>
                        <van-tab :title="$t('邀请记录')">
                            <template #title>
                                <span class="text-[12px] font-semibold"
                                    :style="{ color: active == 1 ? '#FF5C02' : '#FFC3A2' }">{{ $t('获取记录') }}</span>
                            </template>
                            <div class="px-[20px]">
                                <div class="turnable-record-item flex justify-between flex-wrap items-center mb-[8px]" v-for="item in invitedRecord">
                                    <div class="ml-[16px] flex flex-col mt-[8px] mb-[8px] flex-wrap">
                                        <span class="text-[12px] font-medium">{{ $t('购买广告位') }}</span>
                                        <!-- <span class="text-[12px] font-medium">{{ item.user_name
                                        }}</span> -->
                                        <span class="text-[12px] font-medium">{{ item.create_time }}</span>
                                    </div>
                                    <div class="mr-[16px] flex justify-end shrink-0">
                                        <span class="text-[15px] font-semibold text-[#FFFB00] text-right">{{
                                            $t('抽奖次数+1') }}</span>
                                    </div>
                                </div>
                            </div>
                        </van-tab>
                    </van-tabs>
                </div>
            </div>
        </van-popup>
        <van-popup v-model:show="showSmallMoney" :close-on-click-overlay="false"
            class=" p-[30px] w-full bg-transparent">
            <div class="bg-white rounded-[10px] border border-[#FF5001] mt-[53px] flex flex-col items-center relative">
                <img :src="$getImageUrl('icon_trunable_small.png')" class="w-[100px] h-[100px] object-cover">
                <div class="w-full px-[10px] text-center">
                    <span class="text-black text-[12px] font-medium">{{ $t('恭喜您，获得') }}
                        <span class="text-[#15CF00] font-bold text-[12px]">{{ $sysCurrency }} {{ moneyVal }}</span>
                        {{ $t('的奖励') }}
                    </span>
                </div>
                <div class="py-[16px] px-[30px]">
                    <van-button type="primary" block :border="none" @click="smallAction">
                        <span class=" font-bold px-[50px]">{{ $t("确定") }}</span>
                    </van-button>
                </div>
            </div>

        </van-popup>
        <van-popup v-model:show="showLargeMoney" :close-on-click-overlay="false"
            class=" p-[30px] pb-[80px] w-full bg-transparent ">
            <div class="bg-white rounded-[10px] border border-[#FF5001] flex flex-col items-center relative">
                <!-- <img :src="$getImageUrl('icon_large_money_bg.png')" class="absolute top-[-140px] w-[290px] h-[135px]"
                    alt="">
                <img :src="$getImageUrl('icon_trunable_large.png')" class=" absolute top-[-40px] w-[93px] h-[90px]"
                    alt=""> -->
                <img :src="$getImageUrl('Icon_big.png')" class="w-[150px] h-[150px] object-cover"
                    style="transform: rotate(45deg);">
                <div class="w-full px-[10px] text-center">
                    <span class="text-black text-[12px] font-medium">{{ $t('恭喜您，抽中大奖') }}：
                        <span class="text-[#15CF00] font-bold text-[12px]">{{ $sysCurrency }} {{ moneyVal }}</span>
                        {{ $t('完成任务后即可获得奖励') }}
                    </span>
                </div>
                <div class="py-[16px]  px-[30px]">
                    <van-button type="primary" block :border="none" @click="largeAction">
                        <span class=" font-bold px-[50px]">{{ $t("确定") }}</span>
                    </van-button>
                </div>
            </div>
            <img :src="$getImageUrl('Group1.png')"
                class="absolute top-[60px] left-[-6px] w-[150px] h-auto object-contain" alt="">
            <div>
                <img :src="$getImageUrl('Group1.png')" class="absolute top-[0px] right-[0px] w-[150px] h-auto object-contain transform rotate-0 scale-x-[-1]
                " alt="">
            </div>
        </van-popup>
        <van-popup v-model:show="showLastMoney" :close-on-click-overlay="false"
            class=" p-[30px] pb-[80px] w-full bg-transparent ">
            <div class="bg-white rounded-[10px] border border-[#FF5001] flex flex-col items-center relative">
                <!-- <img :src="$getImageUrl('icon_large_money_bg.png')" class="absolute top-[-140px] w-[290px] h-[135px]"
                    alt="">
                <img :src="$getImageUrl('icon_trunable_large.png')" class=" absolute top-[-40px] w-[93px] h-[90px]"
                    alt=""> -->
                <img :src="$getImageUrl('Icon_big.png')" class="w-[150px] h-[150px] object-cover"
                    style="transform: rotate(45deg);">
                <div class="w-full px-[10px] text-center">
                    <span class="text-black text-[12px] font-medium">{{ $t('领取成功！奖励已发放账户') }}
                    </span>
                </div>
                <div class="py-[16px] px-[30px]">
                    <van-button type="primary" block :border="none" @click="lastAction">
                        <span class=" font-bold px-[50px]">{{ $t("确定") }}</span>
                    </van-button>
                </div>
            </div>
            <img :src="$getImageUrl('Group1.png')"
                class="absolute top-[40px] left-[-6px] w-[150px] h-auto object-contain" alt="">
            <div>
                <img :src="$getImageUrl('Group1.png')" class="absolute top-[0px] right-[0px] w-[150px] h-auto object-contain transform rotate-0 scale-x-[-1]
                " alt="">
            </div>
        </van-popup>

        <van-popup v-model:show="showJiaMoney" :close-on-click-overlay="false" class=" p-[30px] w-full bg-transparent">
            <div class="bg-white rounded-[10px] pt-[30px] border border-[#FF5001] flex flex-col items-center relative">
                <!-- <img :src="$getImageUrl('icon_large_money_bg.png')" class="absolute top-[-140px] w-[290px] h-[135px]"
                    alt="">
                <img :src="$getImageUrl('icon_trunable_large.png')" class=" absolute top-[-40px] w-[93px] h-[90px]"
                    alt=""> -->
                <img :src="$getImageUrl('icon_boostpack.png')" class="w-[100px] h-[100px] object-cover">
                <div class="w-full px-[10px] text-center">
                    <span class="text-black text-[12px] font-medium">{{ $t('恭喜您，抽中') }}{{ nameVal }}{{ $t('提现完成值') }}
                        <span class="text-[#15CF00] font-bold text-[12px]">{{ moneyVal }}</span>
                    </span>
                </div>
                <div class="py-[16px] px-[30px]">
                    <van-button type="primary" block :border="none" @click="jiaAction">
                        <span class=" font-bold px-[50px]">{{ $t("确定") }}</span>
                    </van-button>
                </div>
            </div>
        </van-popup>
    </div>
</template>

<script setup>
import { nextTick, onMounted, ref, reactive, getCurrentInstance } from 'vue';
import * as echarts from 'echarts';
import router from '../router';
import { TeamService } from '@/api/team';
import { useI18n } from 'vue-i18n';
const { t } = useI18n()
import { Locale } from 'vant';
import enUS from 'vant/es/locale/lang/en-US';
import { AuthService } from "@/api/home";
Locale.use('en-US', enUS);
import { TurntableService } from '@/api/turntable'
import { getImageUrl } from '@/utils/getImageUrl.js';
import { showToast } from 'vant';
const { proxy } = getCurrentInstance();
import { useUserStore } from "@/store/userinfo";
const store = useUserStore();

const largeAction = () => {
    rotatePro.value = 0;
    fetchPddInfo();
    showLargeMoney.value = false;
}
const smallAction = () => {

    rotate.value = 0;
    fetchPddInfo();
    showSmallMoney.value = false;
}
const lastAction = () => {
    rotatePro.value = 0;
    rotate.value = 0;
    fetchPddInfo();
    showLastMoney.value = false;
}
const jiaAction = () => {
    rotatePro.value = 0;
    fetchPddInfo();
    showJiaMoney.value = false;
}

const active = ref(0);
const showdetails = ref(false);
const drawRecord = ref([]);
const invitedRecord = ref([]);
const toDetails = async () => {
    const response = await TurntableService.getPddDrawRecord({ page: 1, limit: 50, });
    drawRecord.value = response.data;

    // const response1 = await TurntableService.getPddInvitedRecord({ page: 1, limit: 50, });
    const response1 = await TurntableService.financeProductRecord({ page: 1, limit: 50, });
    invitedRecord.value = response1.data;

    showdetails.value = true;
}
const content = ref()
const showRule = ref(false);
const toRuleDetail = async () => {
    const res = await AuthService.article_details({ type: "13" })
    content.value = res
    showRule.value = true;
}

const time = ref(0);
const canHelp = ref(false);
const canHelpDone = ref(false);
const helpData = ref([
{ name: t('初级助力包'), id: 'low' },
{ name: t('中级助力包'), id: 'middle' },
{ name: t('高级助力包'), id: 'high' },
{ name: t('初级助力包'), id: 'low' },
{ name: t('中级助力包'), id: 'middle' },
{ name: t('高级助力包'), id: 'high' },
]);

const turntableInfo = ref({ prizes: [] })
const differenceStr = ref('');

const spinning = ref(false);
const isTouchAction = ref(false);
const rotate = ref(0);
const rotatePro = ref(0);
// 基础旋转圈数
const runNum = 10;

const moneyVal = ref('0');
const showSmallMoney = ref(false);
const showLargeMoney = ref(false);
const nameVal = ref('');
const showJiaMoney = ref(false);
const showLastMoney = ref(false);

const spin = async () => {
    if (turntableInfo.value.turntable_count == 0) {
        return showToast(t("暂无抽奖次数"));
    }
    if (isTouchAction.value) return; // 防止重复点击
    isTouchAction.value = true;
    const response = await TurntableService.onPddTurntable({ id: turntableInfo.value.id }, () => {
        spinning.value = false
        isTouchAction.value = false;
    })
    spinning.value = true;
    const wheelElement = document.querySelector('.wheel');
    rotate.value = rotate.value + getCurrentRotation(wheelElement);
    setTimeout(() => {
        toSpinningAction(response);
    }, 500);
}

const toSpinningAction = (response) => {
    turntableInfo.value.turntable_count--
    const deg = (360 / turntableInfo.value.prizes.length) * turntableInfo.value.prizes.findIndex((item) => item.id == response.id) + (360 / turntableInfo.value.prizes.length / 2);
    rotate.value = runNum * 360 + (rotate.value - (rotate.value % 360)) - deg - 22.5;
    // 等动画结束后显示结果
    setTimeout(() => {
        moneyVal.value = response.value;
        if (response.is_big_prize) {
            showLargeMoney.value = true;
        } else {
            showSmallMoney.value = true;
        }
        setTimeout(() => {
            spinning.value = false;
            isTouchAction.value = false;
        }, 500);
    }, 5000); // 动画时间为3秒
}

const spinPro = async () => {
    if (canHelpDone.value) {
        return;
    }
    if (turntableInfo.value.turntable_count == 0) {
        return showToast(t("暂无抽奖次数"));
    }
    if (isTouchAction.value) return; // 防止重复点击
    isTouchAction.value = true;
    const response = await TurntableService.onPddProgressTurntable({ record_id: turntableInfo.value.id }, () => {
        spinning.value = false;
        isTouchAction.value = false;
    })
    spinning.value = true;
    const wheelElement = document.querySelector('.wheel');
    rotatePro.value = rotatePro.value + getCurrentRotation(wheelElement);
    setTimeout(() => {
        toSpinningProAction(response);
    }, 500);

}
const toSpinningProAction = (response) => {
    turntableInfo.value.turntable_count--
    const deg = (360 / helpData.value.length) * helpData.value.findIndex((item) => item.id == response.type) + (360 / helpData.value.length / 2);
    rotatePro.value = runNum * 360 + (rotatePro.value - (rotatePro.value % 360)) - deg;
    // 等动画结束后显示结果
    setTimeout(() => {
        const i = helpData.value.find((item) => item.id == response.type)
        nameVal.value = i.name;
        showJiaMoney.value = true;
        moneyVal.value = response.increment + '%';
        setTimeout(() => {
            spinning.value = false;
            isTouchAction.value = false;
        }, 500);
    }, 5000); // 动画时间为3秒
}

const getCurrentRotation = (element) => {
    const computedStyle = getComputedStyle(element);
    const transform = computedStyle.transform || computedStyle.webkitTransform || computedStyle.mozTransform;
    if (transform !== 'none') {
        const matrix = transform.match(/^matrix\((.+)\)$/)[1].split(', ');
        const a = parseFloat(matrix[0]);
        const b = parseFloat(matrix[1]);
        const angle = Math.atan2(b, a);
        return angle * (180 / Math.PI); // Convert to degrees
    }
    return 0;
};

const fetchPddInfo = async () => {
    const response = await TurntableService.getPddTurntable()
    turntableInfo.value = response;
    canHelp.value = response.progress_model != 0;
    if (turntableInfo.value.prizes) {
    } else {
        time.value = Number(response.time_left * 1000);
        canHelpDone.value = Number(response.progress) >= 100;
        differenceStr.value = (100 - Number(response.progress)).toFixed(2).toString();
    }
}

const onPddReceive = async () => {
    if (!canHelpDone) {
        return;
    }
    const response = await TurntableService.onPddReceiveTurntable({ record_id: turntableInfo.value.id });
    showLastMoney.value = true;
    moneyVal.value = response.value;
}
const pddRecords = ref();
const getPddRecords = async () => {
    const response = await TurntableService.onPddReceivingRecords({ page: 1, limit: 30, });
    pddRecords.value = [];
    if (response.data.length < 3) {
        pddRecords.value.push(response.data);
    } else {
        for (let i = 0; i < response.data.length; i += 3) {
            pddRecords.value.push(response.data.slice(i, i + 3));
        }
        if (response.data.length % 3 !== 0) {
            pddRecords.value.push(response.slice(response.data.length - (response.data.length % 3)));
        }
    }
}

onMounted(() => {
    fetchPddInfo();
    getPddRecords();
    store.getUserInfo();
})

const shareData = ref([{ img: getImageUrl('icon_share_telegram.png'), name: 'Telegram' },
{ img: getImageUrl('icon_share_whatsapp.png'), name: 'WhatsAPP' },
{ img: getImageUrl('Instagram.png'), name: 'Instagram' },
{ img: getImageUrl('icon_share_facebook.png'), name: 'Facebook' },
]);
const share = (type) => {
    let newurl = encodeURIComponent(store.userInfo?.user_info?.share_link)
    console.log(newurl, "111111")
    const shareLinkData = {
        Facebook: `https://www.facebook.com/sharer/sharer.php?u=${newurl}`
        ,
        WhatsAPP: `https://api.whatsapp.com/send?text=Check%20out%20this%20awesome%20website%3A%20${newurl}`
        ,
        Telegram: `https://t.me/share/url?url=${newurl}`,
        Instagram: `https://www.instagram.com/stories/upload/?url=${newurl}`
    };
    location.href = shareLinkData[type]
};
</script>

<style lang="less" scoped>
.content {
    // background: url("../assets/images/turntable_back_one.png") no-repeat top center;
    background-size: var(--maxWidth) auto;
    min-height: 100vh;
}

.content-two {
    background: url("../assets/images/turntable_back_two.png") no-repeat top center;
    background-size: var(--maxWidth) auto;
    min-height: 100vh;
}

:deep(.van-nav-bar) {
    position: fixed;
    z-index: var(--van-nav-bar-z-index);
    line-height: var(--van-line-height-lg);
    text-align: center;
    background: #00000000;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
}

.aspect-square {
    background-image: url(@/assets/images/icon_wheel_bg.png);
    background-size: 120% auto;
    background-position: center;
    animation: 20s t-border infinite linear;
    pointer-events: none;
}

.regras::-webkit-scrollbar {
    display: none;
    /* Webkit browsers */
}

@keyframes t-border {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(-360deg);
    }
}


.sector {
    width: 100%;
    height: 40%;
    transform-origin: 50% 100%;
}

.help-sector {
    width: 30%;
    height: 40%;
    transform-origin: 50% 100%;
}

.wheel {
    transform-origin: center;
    transition: transform 5s cubic-bezier(0.25, 0.1, 0.25, 1);
}

.wheel-slow {
    animation: slow-rotate 60s linear infinite;
}

@keyframes slow-rotate {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.circular-box {
    position: absolute;

    &:nth-child(2n-1) {
        .circular {
            animation-delay: 0.5s;
        }
    }
}

.circular {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #e5e27f;
    box-shadow: 0 0 2px #e5e27f, 0 4px 8px rgba(201, 223, 122, 0.6);
    transform: translateY(-142px);
    animation: circular 1s infinite;
}

@keyframes circular {
    0% {
        background: #e5e27f;
        box-shadow: 0 0 2px #e5e27f, 0 4px 8px rgba(201, 223, 122, 0.6);

    }

    100% {
        background: #FFF;
        box-shadow: 0 0 2px #FFF inset, 0 4px 8px rgba(240, 234, 234, 0.6);
    }
}

.wheel-bottom-background {
    background: linear-gradient(0deg, #11B4F0 0%, #2DFFC6 100%);
}


.van-progress {
    position: relative;
    height: var(--van-progress-height);
    background: rgba(255, 255, 255, 0.2) !important;
    border-radius: 50px;
}

.help-button-background {
    background: linear-gradient(89deg, #FF4901 0%, #FF7801 100%);
    border-radius: 6px 6px 6px 6px;
}

.van-count-down {
    color: #FF5C02;
    font-size: 14px;
    font-weight: 700;
    line-height: var(--van-count-down-line-height);
}

// .popup-background {
//     background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
//     box-shadow: 0px 1px 4px 0px rgba(0, 0, 0, 0.15);
//     border-radius: 18px;
// }

:deep(.van-tabs__line) {
    position: absolute;
    bottom: 2.5rem;
    left: 0;
    z-index: 1;
    width: var(--van-tabs-bottom-bar-width);
    height: var(--van-tabs-bottom-bar-height);
    background: #FF5C02;
    border-radius: var(--van-tabs-bottom-bar-height);
}

.turnable-record-item {
    background: linear-gradient(89deg, #FF4901 0%, #FF6F01 100%);
    border-radius: 10px;
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
    backdrop-filter: blur(5px);
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

:deep(strong) {
    color: #000000;
}

:deep(p) {
    color: #000000;
}


:deep(li) {
    color: #000000;
}

:deep(h3) {
    color: #000000;
}
</style>