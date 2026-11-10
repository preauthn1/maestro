<template>
    <div class=" pb-[20px]">
        <div class="">
            <van-nav-bar left-arrow :title="$t('快速交易中心')" @click-left="!down ? router.go(-1) : ''"
                @click-right="toRecord">
                <template #left>
                    <van-icon name="arrow-left" color="#FF5001" />
                </template>
                <template #title>
                    <span class="text-[11px] font-semibold">{{ $t('快速交易中心') }}</span>
                </template>
                <template #right>
                    <van-icon name="todo-list-o" size="15" />
                </template>
            </van-nav-bar>
            <!--  -->
            <div v-if="!is_circle">
                <div class="mt-[32px] text-center flex flex-col items-center justify-between">
                    <div class="text-center text-[13px] font-semibold">{{ $t('使用 Meta Deals 进行广告库存的自动化交易') }}</div>
                    <!-- <div class="text-center text-[13px] font-semibold">{{ $t('进行广告库存的自动交易') }}</div> -->
                    <div class="w-[150px] h-[150px] relative rounded-full flex flex-col items-center justify-center mt-[10px]"
                        :class="[isCanMatch && store.currentType == 4 ? 'center-background-linear_4' : isCanMatch && store.currentType == 3 ? 'center-background-linear_3' : isCanMatch && store.currentType == 2 ? 'center-background-linear_2' : isCanMatch && store.currentType == 1 ? 'center-background-linear_1' : 'bg-[#3B3843]']"
                        @click="ispasswordpopup">
                        <span class="text-[13px] font-semibold">
                            {{ isCanMatch ? $t("点击开始交易") : $t("暂无次数") }}
                        </span>
                        <!-- <span class="text-[12px]">（{{ waitPay_project?.product_order_count |
                            0 }}/{{ waitPay_project?.product_count |
                                0 }}）</span>  -->
                        <img :src="$getImageUrl('shou.png')"
                            class=" absolute w-[50px] h-auto bottom-[7px] right-[-10px]" alt="" v-if="isCanMatch">

                    </div>
                </div>

                <div
                    :class="[`mx-[11px] px-[20px] mt-[20px] py-[10px]`, store.currentType == 1 ? 'notice_rich_1' : store.currentType == 2 ? 'notice_rich_2' : store.currentType == 3 ? 'notice_rich_3' : store.currentType == 4 ? 'notice_rich_4' : '']">
                    <span v-html="rule?.content"></span>
                    <!-- <div v-html="rule?.content"></div> -->
                </div>

                <van-list v-model:loading="loading" :finished="finished" :loading-text="$t('加载中')"
                    :finished-text="$t('没有更多了')" @load="onLoad">
                    <div class="text-[13px] px-[11px] font-semibold  mb-[10px]"
                        v-if="waitPay_project && waitPay_project.wait_list.length > 0">
                        {{ $t("待支付的广告库存") }}
                    </div>
                    <div class="mt-[18px] mx-[11px] mb-[30px]" v-for="item in waitPay_project.wait_list" :key="item.id"
                        v-if="waitPay_project && waitPay_project.wait_list.length > 0">
                        <div class="content">
                            <div class="flex">
                                <span
                                    class=" bg-[#FF5C02] px-4 py-2 text-[12px] font-medium text-center jinxabs text-white">
                                    {{ $t('广告库存') }}
                                </span>
                            </div>
                            <div class="px-10 pb-4">
                                <div class="flex justify-between items-start  mt-[10px]">
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-black font-medium whitespace-nowrap">{{
                                            $t('订单交易ID')
                                        }}:</span>
                                    </div>
                                    <div class="text-[12px] text-black font-medium text-right">
                                        <span class="text-[12px] text-black font-medium break-all">
                                            {{ item?.order_no }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-[10px]">
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-black font-medium">{{ $t('预计收益') }}:</span>
                                    </div>
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-[#FF5C02] font-medium">
                                            {{ item?.profit_money }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-[10px]">
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-black font-medium">{{ $t('投放周期') }}:</span>
                                    </div>
                                    <div class="text-[12px] text-black font-medium text-right">
                                        <span class="text-[12px] text-[#FF5C02] font-medium ">
                                            {{ item?.cycle_str }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-start mt-[10px]">
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-black font-medium whitespace-nowrap">
                                            {{ $t('库存价格') }}:
                                        </span>
                                    </div>
                                    <div class="text-[12px] text-[#FF5C02] font-medium text-right">
                                        <span class="text-[12px] text-[#FF5C02] font-medium ">{{ item?.money }} </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-start mt-[10px]"
                                    v-for="attach in item.other_info">
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-black font-medium whitespace-nowrap">
                                            {{ attach.title }}:
                                        </span>
                                    </div>
                                    <div class="text-[12px] text-[#FF5C02] font-medium text-right">
                                        <span class="text-[12px] text-[#FF5C02] font-medium break-all">
                                            {{ attach.content }}
                                        </span>
                                    </div>
                                </div>

                                <van-button type="primary" class="w-full bg-[#0034B4] mt-8"
                                    @click="router.push('/rechargechannels')">
                                    <span class="text-[12px] font-semibold">
                                        {{ $t("充值") }}
                                    </span>
                                </van-button>
                            </div>

                        </div>
                    </div>
                    <div class="text-[12px] px-[11px] font-semibold my-[10px]">{{ $t("进行中的广告库存") }}</div>
                    <div class="mt-[18px] ml-[16px] mr-[16px] mb-[30px]" v-for="item in projectList" :key="item.id">
                        <div class="content">
                            <div class="flex">
                                <span
                                    class=" bg-[#FF5C02] px-4 py-2 text-[12px] font-medium text-center jinxabs text-white">
                                    {{ $t('广告库存') }}
                                </span>
                            </div>
                            <div class="px-10 pb-4">
                                <div class="flex justify-between items-start  mt-[10px]">
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-black font-medium whitespace-nowrap">
                                            {{ $t('订单交易ID') }}:
                                        </span>
                                    </div>
                                    <div class="text-[12px] text-black font-medium text-right">
                                        <span class="text-[12px] text-black font-medium break-all">
                                            {{ item?.order_no }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-[10px]">
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-black font-medium">{{ $t('预计收益') }}:</span>
                                    </div>
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-[#FF5C02] font-medium">
                                            {{ item?.profit_money }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-[10px]">
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-black font-medium">{{ $t('投放周期') }}:</span>
                                    </div>
                                    <div class="text-[12px] text-black font-medium text-right">
                                        <span class="text-[12px] text-[#FF5C02] font-medium ">
                                            {{ item?.cycle_str }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-start mt-[10px]">
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-black font-medium whitespace-nowrap">
                                            {{ $t('库存价格') }}:
                                        </span>
                                    </div>
                                    <div class="text-[12px] text-[#FF5C02] font-medium text-right">
                                        <span class="text-[12px] text-[#FF5C02] font-medium ">{{ item?.money }} </span>
                                    </div>
                                </div>
                                <div class="flex justify-between items-start mt-[10px]"
                                    v-for="attach in item.other_info">
                                    <div class="text-[12px] text-black font-medium">
                                        <span class="text-[12px] text-black font-medium whitespace-nowrap">
                                            {{ attach.title }}:
                                        </span>
                                    </div>
                                    <div class="text-[12px] text-[#FF5C02] font-medium text-right">
                                        <span class="text-[12px] text-[#FF5C02] font-medium break-all">
                                            {{ attach.content }}
                                        </span>
                                    </div>
                                </div>

                                <!-- <van-button type="primary" class="w-full bg-[#0034B4] mt-8"
                                    @click="router.push('/rechargechannels')"><span class="text-[12px] font-semibold">{{ $t("充值")
                                    }}</span></van-button> -->
                            </div>

                        </div>
                    </div>
                </van-list>
            </div>
            <div class="mt-[50px]" v-else>
                <div class="flex justify-center ">
                    <van-circle v-model:current-rate="currentRate" stroke-linecap="butt" :color="gradientColor[store.currentType - 1]"
                        :rate="rate" layer-color="#3C2F29" stroke-width="50" speed="30" size="150px"
                        start-position="bottom">
                        <template #default>
                            <div class=" flex items-center justify-center w-full h-full text-[18px] font-semibold">
                                {{ curcircle.toFixed(0) }}%
                            </div>
                        </template>
                    </van-circle>
                </div>
                <div class="text-center text-[12px] font-semibold mt-[20px]">{{ $t("正在竞拍中，请勿中断交易……") }}</div>
                <div
                    :class="[`mx-[11px] px-[20px] mt-[20px] py-[10px]`, store.currentType == 1 ? 'notice_rich_1' : store.currentType == 2 ? 'notice_rich_2' : store.currentType == 3 ? 'notice_rich_3' : store.currentType == 4 ? 'notice_rich_4' : '']">
                    <span v-html="rule?.content"></span>
                    <!-- <div v-html="rule?.content"></div> -->
                </div>
            </div>

            <!-- 匹配成功弹窗 -->
            <van-popup v-model:show="showCenter" :close-on-click-overlay="false"
                :style="{ padding: '20px', 'width': '90%', background: '#FFFFFF', 'border-radius': '15px', border: '1px solid #FF5B01' }">
                <div class="bg-[#FF5C02] jinxabs  px-4 py-2 absolute top-0 left-0">
                    <span class=" text-center  text-white">{{ $t("库存订单详情") }}</span>
                </div>
                <div class="text-black text-center text-[12px] font-bold mt-[20px]">{{ $t('购买成功！点击确认以开始投放') }}</div>
                <div class="relative mt-[20px]">
                    <div class="pb-[40px] pt-[8px]">
                        <div class="flex justify-between items-startr  mt-[10px]">
                            <div class="">
                                <span class="text-[12px] text-black font-medium whitespace-nowrap">
                                    {{ $t('订单交易ID') }}:
                                </span>
                            </div>
                            <div class=" text-right">
                                <span class="text-[12px] text-black font-medium break-all">
                                    {{ waitPay_project_last?.order_no }}
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-[10px]">
                            <div class="">
                                <span class="text-[12px] text-black font-medium">{{ $t('预计收益') }}:</span>
                            </div>
                            <div class="">
                                <span class="text-[12px] text-[#FF5C02] font-medium">
                                    {{ waitPay_project_last?.profit_money }}
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-[10px]">
                            <div class="">
                                <span class="text-[12px] text-black font-medium">{{ $t('投放周期') }}:</span>
                            </div>

                            <div class="text-[13px] text-[#FF5C02] font-medium text-right">
                                <span class="text-[12px] text-[#FF5C02] font-medium">
                                    {{ waitPay_project_last?.cycle_str }}
                                    <!-- {{
                                        waitPay_project_last?.invest_period_type == "hour" ? 'h' :
                                            (waitPay_project_last?.invest_period_type == "minute" ? $t('分钟') : $t('天'))
                                    }} -->
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-[10px]">
                            <div class="text-[12px] text-black font-medium shrink-0">
                                <span class="text-[12px] text-black font-medium">{{ $t('库存价格') }}:</span>
                            </div>
                            <div class="text-[13px] text-[#FF5C02] font-medium text-right">
                                <span class="text-[13px] text-[#FF5C02] font-medium">
                                    {{ waitPay_project_last?.money }}
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-between items-start mt-[10px]"
                            v-for="item in waitPay_project_last?.other_info">
                            <div class="text-[13px] text-black font-medium">
                                <span class="text-[13px] text-black font-medium whitespace-nowrap">
                                    {{ item.title }}:
                                </span>
                            </div>
                            <div class="text-[13px] text-[#FF5C02] font-medium text-right">
                                <span class="text-[13px] text-[#FF5C02] font-medium">
                                    {{ item.content }}
                                </span>
                            </div>
                        </div>
                        <div class="flex justify-center mx-[20px]">
                            <van-button class="w-full bg-[#FF5B01] rounded-[10px] mt-[20px] text-[20px]"
                                @click="confirmBtn">
                                <span class="text-[16px] letter font-medium">{{ $t("确认") }}</span>
                            </van-button>
                        </div>

                    </div>

                </div>
                <div class="text-[#286EE0] text-center text-[12px] font-medium">{{ $t('您可以进入购买记录查看详情') }}</div>

            </van-popup>
            <!-- 输入密码弹窗 -->
            <van-popup v-model:show="showPass" position="bottom" round
                :style="{ height: '30%', 'max-width': '42.5rem', left: '50%', transform: 'translateX(-50%)', background: '#ffffff', border: '1px solid #FF5B01' }">
                <div class="password px-[20px] py-[5px]">
                    <span class="w-full text-[12px] font-medium ">{{ $t("填写编码") }}</span>
                </div>
                <div class="px-[57px] pt-[41px]">
                    <van-field v-model="password" maxlength="6" type="password" :placeholder="$t('请填写您的编码')" />

                    <van-button class="w-full bg-[#FF5B01] rounded-[6px]" @click="verify">
                        <span class="text-[13px] font-semibold">{{ $t("确认") }}</span>
                    </van-button>
                    <van-button class="w-full cancel_btn mt-4 " @click="closePassView">
                        <span class="text-[13px] font-semibold">{{ $t("取消") }}</span>
                    </van-button>
                </div>
            </van-popup>

        </div>
    </div>

    <van-dialog v-model:show="showPubDialog"
        style="padding:0px 10px 10px;background:#FFFFFF; border: 1px solid #FF5B01;" :close-on-click-overlay="false"
        show-cancel-button>
        <template #title>
            <div class="absolute top-0 left-0">
                <span class=" bg-[#FF5C02] px-5 py-2 text-center jinxabs text-white text-[13px]">{{ $t("交易提示")
                }}</span>
            </div>
        </template>
        <template #default>
            <div class="py-[16px] px-[16px] text-center mt-[40px]">
                <span class="text-[13px] text-black font-semibold">{{ $t('您预算内的库存已售罄。是否启用AI自动补差，完成更高价位的库存购买？') }}</span>
            </div>
            <div class="pb-[10px] text-[#286EE0] w-full text-center text-[12px] font-bold">
                ({{ $t('秒后自动启用', { time: count }) }})
            </div>
        </template>
        <template #footer>
            <div class="flex items-center px-[16px] w-full justify-between gap-3 text-white">
                <div class="flex-1 text-center  py-[10px] rounded-[10px]"
                    style="background:linear-gradient( 89deg, #FF4901 0%, #FF7801 100%)" @click="confirmAction">
                    <span class="text-[13px] font-semibold">{{ $t('启用') }}</span>
                </div>
                <div class="flex-1 text-center py-[10px] rounded-[10px]"
                    style="background: linear-gradient( 89deg, #FF6E35 0%, #FFAD72 100%);" @click="cancelAction">
                    <span class="text-[13px] font-semibold">{{ $t('取消') }}</span>
                </div>

            </div>
        </template>
    </van-dialog>
    <van-overlay :show="showloading" style="z-index: 9999; background: rgba(0, 0, 0, 0.3);">
        <van-loading type="spinner" class="loadingtext" style="z-index: 9999;" v-if="isloading" />
    </van-overlay>
</template>

<script setup>
import { onMounted, ref, reactive, onUnmounted } from 'vue';
import { useRoute, useRouter } from "vue-router";
import { AuthService } from '@/api/home';
import { showToast } from 'vant';
import { useI18n } from "vue-i18n";
import { useUserStore } from '../store/userinfo';
import { usevipStore } from '../store/vip';
import { useSystemStore } from '../store/system'
const store = useUserStore();
const vipstore = usevipStore()
const { t } = useI18n();
// const sysstore = useSystemStore()
// const showGame = ref(false);
const showPass = ref(false);
const password = ref('');
const showCenter = ref(false);
const router = useRouter();
const route = useRoute();
const showloading = ref(false);
const showPubDialog = ref(false);
const isloading = ref(false)
//超额匹配参数
const overdue_config = ref();
//是否点击了 启用或取消 按钮
const isBtn = ref(false)
//是
let confirmTimer = null;
const confirmAction = () => {
    isBtn.value = true;
    let confirCount = overdue_config.value?.finance_product_button_count_down
    //判断开关
    if (overdue_config.value?.finance_product_yes_can_click == 0) {
        showloading.value = true
        isloading.value = true;
        if (confirmTimer) clearInterval(confirmTimer); // 防止多次触发
        confirmTimer = setInterval(() => {
            confirCount--;
            if (confirCount <= 0) {
                // showPubDialog.value = false;
                // showloading.value = false;
                // isloading.value = false;
                clearInterval(confirmTimer)
                overdue_match()
            }
        }, 1000);
    }
    else {
        // showPubDialog.value = false;
        overdue_match()
    }
    //
}
//否
let cancelTimer = null;
const cancelAction = () => {
    isBtn.value = true;
    let confirCount = overdue_config.value?.finance_product_button_count_down
    //判断开关
    if (overdue_config.value?.finance_product_no_can_click == 0) {
        showloading.value = true
        if (cancelTimer) clearInterval(cancelTimer);
        cancelTimer = setInterval(() => {
            confirCount--;
            if (confirCount <= 0) {
                // showPubDialog.value = true;
                // showloading.value = false;
                clearInterval(cancelTimer)
                cancelTimer = null
                overdue_match()
            }
        }, 1000);
    } else {
        showPubDialog.value = false;
        isover.value = false;
        reset(1)
    }
}

const waitPay_project = ref()
const waitPay_project_last = ref()
let currentRate = ref(0)
const rate = ref(100)
const gradientColor = [
    {'0%':'#0075DC'},
    {'0%':'#003093'},
    {'0%':'#009942'},
    {'0%': '#FF5A01'},

]
const rule = ref()
function toRecord() {
    if (down.value) return;
    router.push('/investmentrecords');
}
const curcircle = ref(0)
const isCanMatch = ref(false);
const projectList = ref([])
const pageInfo = reactive({
    page: 1,
    limit: 10,
})
const is_circle = ref(false)
const loading = ref(false)
const finished = ref(false)

const ispasswordpopup = async () => {
    if (isover.value) return;
    //判断外层编码是否过期;
    let isExpired = await expired();
    if (isExpired) return;
    let curtime = Math.floor(Date.now() / 1000);
    //普通区
    if (store.is_precision == 1) {
        if (!isCanMatch.value) {
            return showToast(t("当前暂无匹配次数"))
        }
        suanfa();
    } else if (store.is_precision == 2) {
        if (store.precisionpassword.expire_time) {
            if (store.precisionpassword.expire_time > curtime) {
                if (!isCanMatch.value) {
                    return showToast(t("当前暂无匹配次数"))
                }
                suanfa()
            } else {
                if (!isCanMatch.value) {
                    return showToast(t("当前暂无匹配次数"))
                }
                showPass.value = true;
            }
        } else {
            if (!isCanMatch.value) {
                return showToast(t("当前暂无匹配次数"))
            }
            showPass.value = true;
        }
    } else {
        if (store.pipeipassword.expire_time) {
            if (store.pipeipassword.expire_time > curtime) {
                if (!isCanMatch.value) {
                    return showToast(t("当前暂无匹配次数"))
                }
                //是否解锁
                suanfa()
            } else {
                if (!isCanMatch.value) {
                    return showToast(t("当前暂无匹配次数"))
                }
                showPass.value = true;
            }
        } else {
            if (!isCanMatch.value) {
                return showToast(t("当前暂无匹配次数"))
            }
            showPass.value = true;
        }
    }

}

//验证编码
const verify = async () => {
    if (!password.value) {
        return showToast(t("请输入编码"))
    }
    let curtime = Math.floor(Date.now() / 1000);
    const res = await AuthService.checkPassword({ "level_id": route.query.id, "password": password.value, "type": store.currentType})
    if (res.code == 0) {
        showPass.value = false
        if (store.is_precision == 2) {
            store.setprecisionpassword(password.value, curtime + res.time_left)
        };
        if (store.is_precision == 3) {
            store.setpipassword(password.value, curtime + res.time_left)
        }
        suanfa()

    } else {
        return showToast(res.msg)
    }
}
const isover = ref(false)
//点击购买
const pipei = async () => {

    try {
        const res = await AuthService.match({ "level_id": route.query.id, "password": store.is_precision == 1 ? store.encode.bianma : store.is_precision == 2 ? store.precisionpassword.bianma : store.pipeipassword.bianma, type: store.currentType })
        get_finance_product_order();


    } catch (error) {
        clearTimeout(suanfaTimer);
        reset(1)
    }
}
//广告位不足，超额匹配广告位 overmatch

const overdue_match = async () => {
    try {
        const res = await AuthService.overmatch({ "level_id": route.query.id, "password": store.is_precision == 1 ? store.encode.bianma : store.is_precision == 2 ? store.precisionpassword.bianma : store.pipeipassword.bianma, type: store.currentType})
        get_finance_product_order(1);
    } catch (error) {
        reset(1)
    }
}
const ismore = ref(false)
//是否解锁
// const isUnlock = async () => {
//     if (vipstore.taskInfo.level_list.filter(item => item.level_id == route.query.id)[0].status == 0) {
//         //未解锁的情况下去解锁且拉取vip列表
//         try {
//             const res = await AuthService.upLevel({ "level_id": route.query.id })
//             if (res.code == 0) {
//                 vipstore.taskInfo.level_list.forEach(element => {
//                     if (element.level_id == route.query.id)
//                         element.status = 1;
//                 });
//                 pipei();
//             } else {
//                 clearTimeout(suanfaTimer);
//                 reset(1)
//                 showToast(res.msg)
//             }
//         } catch (error) {
//             clearTimeout(suanfaTimer);
//             reset(1)
//         }

//     } else {
//         pipei();
//     }

// }
//定时轮询匹配结果
const resultTimer = ref(null);
const get_finance_product_order = async (num) => {
    try {

        if (resultTimer.value) {
            clearInterval(resultTimer.value);
            resultTimer.value = null;
        }
        if (num == 1) {
            showPubDialog.value = true;
            isloading.value = true;
            showloading.value = true;
        }
        resultTimer.value = setInterval(async () => {
            let res = null;
            if (!ismore.value) {
                res = await AuthService.get_finance_product_order({ "level_id": route.query.id });
                ismore.value = true;
            }
            if (res) {
                ismore.value = false;

                //res.code等于0有数据成功的情况下，执行以下逻辑,
                if (res.data && res.data.id) {
                    if (num == 1) {
                        showPubDialog.value = false;
                        isloading.value = false;
                        showloading.value = false;
                        isover.value = false;
                    }
                    //进度条关闭
                    clearTimeout(suanfaTimer);
                    //关闭轮询的定时器
                    clearInterval(resultTimer.value);
                    isGenerating.value = false
                    if (res.code == 0) {
                        if (num == 1) {
                            isover.value = false;
                        }
                        waitPay_project_last.value = res.data;
                        reset();
                        getWait()
                    }
                    else {
                        is_circle.value = false;
                        reset(1)
                        showToast(res.msg)
                    }
                }
                //当res.code存在状态码的情况下，请求成功返回错误信息，执行以下逻辑。
                else if (res.code) {

                    if (num == 1) {
                        showPubDialog.value = false;
                        isloading.value = false;
                        showloading.value = false;
                        isover.value = false;
                    }
                    isGenerating.value = false;
                    //进度条关闭
                    clearTimeout(suanfaTimer);
                    //关闭轮询的定时器
                    clearInterval(resultTimer.value);
                    if (res.code == -1) {
                        is_circle.value = false;
                        reset(1)
                        showToast(res.msg)
                    } else if (res.code == -2) {
                        is_circle.value = false;
                        reset(1)
                        if (store.is_precision == 1) {
                            showToast(res.msg)
                            store.clearencode()
                            return router.replace('/newhonouredGuestentory')
                        } else if (store.is_precision == 2) {
                            showToast(res.msg)
                            return store.clearprecisionpassword()
                        } else {
                            showToast(res.msg)
                            return store.clearpipassword()
                        }

                    } else if (res.code == -3) {//执行超额匹配
                        //超额匹配结果
                        if (num == 1) {
                            is_circle.value = false;
                            reset(1)
                            showToast(res.msg)
                        } else {
                            //超额匹配
                            await get_overdue_config();
                            //超额匹配时出现弹框
                            isover.value = true
                            showPubDialog.value = true
                            daojishi();
                        }

                    }
                    else {
                        //其他错误
                        is_circle.value = false;
                        reset(1)
                        showToast(res.msg)
                    }
                }
                else {
                    if (isGenerating.value) {
                        // 如果倒计时没结束，继续循环倒计时
                        if (down.value) {
                            return;
                        }
                        showToast(t('当前访问人数过多，请稍等'))
                        rate.value = 0
                        // is_circle.value = false;
                        currentRate.value = 0
                        curcircle.value = 0
                        suanfa();
                    }
                }
            }
        }, 5000);
    } catch (error) {
        showPubDialog.value = false;
        isloading.value = false;
        showloading.value = false;
        isGenerating.value = false
        isover.value = false;
        reset(1);
        //进度条关闭
        clearTimeout(suanfaTimer);
        //关闭轮询的定时器
        clearInterval(resultTimer.value);
    }
}
//重置
const reset = (num) => {
    down.value = false
    //关闭进度条、初始化数据
    if (num == 1) {
        rate.value = 0
        is_circle.value = false;
        currentRate.value = 0
        curcircle.value = 0
    } else if (num == 2) { //关闭进度条、初始化数据、关闭成功匹配弹框
        rate.value = 0
        is_circle.value = false;
        currentRate.value = 0
        curcircle.value = 0
        showCenter.value = false
    } else {  //显示成功匹配弹框
        showCenter.value = true
        projectList.value = []
        password.value = '';
        pageInfo.page = 1;
        onLoad()
    }


}
//倒计时是否结束,倒计时没有结束进行不了任何操作
const down = ref(false)
let suanfaTimer = null;
// 随机生成进度条
let currentStep = ref(0);
//
//是否继续生成进度条
const isGenerating = ref(false);
const suanfa = () => {
    down.value = true
    let value = 0;
    const maxValue = 100;
    const duration = 90000; // 90 seconds
    const interval = 1000; // Update every 1 second
    const steps = duration / interval; // Total number of steps
    const targetValue = maxValue;
    is_circle.value = true
    currentStep.value = 0
    let randomSeconds = Math.floor(Math.random() * 10) + 1;//1-10随机匹配秒数
    const incrementValue = async () => {

        currentStep.value++;
        const remainingSteps = steps - currentStep.value;
        const remainingValue = targetValue - value;
        let step = 0;

        if (remainingSteps > 0) {
            step = Math.floor(Math.random() * (remainingValue / remainingSteps) * 2);
        }
        value = Math.min(value + step, targetValue);
        rate.value = value
        curcircle.value = value

        if (currentStep.value < steps) {
            if (!isGenerating.value) {
                if (currentStep.value == randomSeconds) {
                    pipei();
                }
            }
            suanfaTimer = setTimeout(incrementValue, interval);

        } else {
            rate.value = 100
            curcircle.value = 100
            isGenerating.value = true;
            down.value = false; // 结束倒计时
            clearTimeout(suanfaTimer);
            suanfaTimer = null;
        }
    };

    incrementValue();
};


const confirmBtn = () => {
    reset(2)
}
const count = ref()
//超额匹配倒计时
let daojishitimer = null;

const daojishi = (num) => {

    count.value = overdue_config.value?.finance_product_dialog_count_down
    if (daojishitimer) clearInterval(daojishitimer);
    daojishitimer = setInterval(() => {
        count.value--;
        if (isBtn.value) {
            clearInterval(daojishitimer);
            isBtn.value = false;
            return;
        }
        if (count.value <= 0) {
            reset(1);
            clearInterval(daojishitimer);
            daojishitimer = null;
            if (showPubDialog.value) {
                overdue_match()
            }
        }
    }, 1000);
}
//取消
const closePassView = () => {
    showPass.value = false;
    password.value = '';
}
//获取
const getWait = async () => {
    const res = await AuthService.finance_product({ "level_id": route.query.id })
    waitPay_project.value = res
    if (waitPay_project.value.product_count > waitPay_project.value.product_order_count) {
        isCanMatch.value = true
        return;
    }
    isCanMatch.value = false;
}

const onLoad = async () => {
    loading.value = true;
    const res = await AuthService.order_list({ ...pageInfo, "status": 2 });
    loading.value = false;
    projectList.value = [...projectList.value, ...res.data]
    if (projectList.value.length < res.total) {
        pageInfo.page++
        return;
    }
    finished.value = true;
}
//最外层编码是否过期
const expired = async () => {
    let curtime = Math.floor(Date.now() / 1000);
    if (store.encode.expire_time) {
        if (store.encode.expire_time < curtime) {
            showToast(t('编码过期'))
            await router.replace('/newhonouredGuestentory');
            return true;
        }
    }
    else {
        showToast(t('编码过期'))
        await router.replace('/newhonouredGuestentory');
        return true;
    }
    return false;
}
//获取超额匹配配置
const get_overdue_config = async () => {
    const res = await AuthService.overdue_config()
    overdue_config.value = res;
}

onMounted(async () => {

    let isExpired = await expired();
    if (isExpired) return;
    if (!route.query.id) return router.replace('/newhonouredGuestentory');
    AuthService.article_details({ type: "7" }).then(res => {
        rule.value = res;
    });
    await getWait()
});
// 页面卸载时清除定时器
onUnmounted(() => {
    if (confirmTimer) {
        clearInterval(confirmTimer);
        confirmTimer = null;
    }
    if (cancelTimer) {
        clearInterval(cancelTimer);
        cancelTimer = null;
    }
    if (daojishitimer) {
        clearInterval(daojishitimer);
        daojishitimer = null;
    }
    if (suanfaTimer) {
        clearTimeout(suanfaTimer);
        suanfaTimer = null;
    }
    if (resultTimer.value) {
        clearInterval(resultTimer.value);
        resultTimer.value = null;
    }

});
</script>

<style scoped>
:deep(.van-dialog) {
    top: 45%;
    width: var(--van-dialog-width);
    overflow: hidden;
    font-size: var(--van-dialog-font-size);
    background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    border-radius: var(--van-dialog-radius);
    backface-visibility: hidden;
    transition: var(--van-dialog-transition);
    transition-property: transform, opacity;
}

.content {
    /* background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px; */
    background: white;
    border-radius: 10px;
    border: 1px solid #FF5C02;
}

.loadingtext {
    height: 100vh;
    /* 设置视口高度 */
    display: flex;
    justify-content: center;
    /* 水平居中 */
    align-items: center;
    /* 垂直居中 */
    margin: 0;
    /* 移除默认外边距 */

}

.center-text-linear {
    font-weight: normal;
    font-size: 13px;
    color: #FFFFFF;
    line-height: 13px;
    /* background: linear-gradient(180deg, #F4C5D7 0%, #F0A96B 33.10546875%, #EF8186 59.0576171875%, #03177D 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent; */
}

.recordlist-title-linear {
    background: #0937AD;
    border-radius: 0px 0px 20px 20px;
    text-align: center;
}

.jinxabs {
    border-radius: 10px 0px 15px 0px;
}

.center-background-linear_1 {
    background: linear-gradient( 270deg, #00ACF0 0%, #0075DC 100%);
    
}

.center-background-linear_2 {
    background: linear-gradient(180deg, #09bbfe, #003093);
}

.center-background-linear_3 {
    background: linear-gradient(270deg, #00E362 0%, #009942 100%);
}

.center-background-linear_4 {
    background: linear-gradient(180deg, #FF9E01 0%, #FF5A01 100%);
}

/* 体验区 */
.notice_rich_1 {
    background: linear-gradient( 270deg, #00ACF0 0%, #0075DC 100%);
    
    border-radius: 10px;
}


/* 未推广 */
.notice_rich_2 {
    background: linear-gradient(270deg, #1460E3 0%, #003184 100%);
    border-radius: 10px;
}

/* 已推广 */
.notice_rich_3 {
    background: linear-gradient(270deg, #00E362 0%, #009942 100%);
    border-radius: 10px;
}

/* 活动区 */
.notice_rich_4 {
    background: linear-gradient(270deg, #FF8400 0%, #FF5900 100%);
    border-radius: 10px;
}

.password {
    position: absolute;
    top: 0px;
    background: #FF5C02;
    border-radius: 15px 0px 15px 0px;
    left: 0;
    /* transform: translateX(-50%); */
}

.colon {
    display: inline-block;
    margin: 0 5px;
    color: #FFFFFF;
    font-size: 20px;
    font-weight: 600;
}

.block {
    display: inline-block;
    width: 36px;
    height: 36px;
    color: #000000;
    font-size: 16px;
    font-weight: 600;
    text-align: center;
    /* background-color: #FFFFFF; */
    background: linear-gradient(45deg, #cbe2f2, #cfeae2, #c5e5c2);
    line-height: 36px;
    border-radius: 5px;
}

.popview-title-linear {
    background: #0937AD;
    border-radius: 0px 0px 5px 5px;
}

.van-cell {
    background-color: white !important;
}

:deep(.van-popup) {
    overflow-y: inherit !important;
}

:deep(.van-field__control) {
    color: #000000 !important;
}

:deep(.van-field__control::placeholder) {
    color: #000000 !important;
    font-size: 11px;
}

.cancel_btn {
    background: linear-gradient(89deg, #FF6E35 0%, #FFAD72 100%);
    box-shadow: 0px 2px 4px 0px rgba(15, 9, 13, 0.1);
    border-radius: 10px;
}


:deep(.notice_rich) {
    span {
        color: white !important;
    }
}

/* :deep(li::marker) {
    color: #3158FF !important;
} */
</style>
