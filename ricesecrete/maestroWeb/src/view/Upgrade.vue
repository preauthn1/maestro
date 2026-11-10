<template>
    <div>
        <GeneralNavBar />
        <div class="mt-[10px]">

            <div class="pt-[5.6rem] px-[11px] pb-[100px]">
                <div class="mt-4">
                    <div class="gradient-border-box w-full">
                        <div class=" pl-2 pr-7 w-full flex items-center py-[10px]">
                            <div class="flex justify-between items-center flex-1"
                                @click="router.push('/directpushtask')">
                                <div class="flex items-center">
                                    <!-- <van-image :src="$getImageUrl('upgrade/qcord1.png')" class="w-[18px] h-[18px]"></van-image> -->
                                    <span class="text-[11px] pl-[8px] font-semibold">{{ $t("推广奖励") }}</span>
                                </div>
                                <van-image :src="$getImageUrl('upgrade/arrow2.png')" class="h-[13px]"></van-image>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-[15px]">
                    <div class="gradient-border-box flex justify-around items-center py-[20px]">
                        <div class="flex flex-col items-center">
                            <span class="text-[12px] text-center font-bold">{{ $t("团队总收入") }}</span>
                            <span class="text-[14px] text-amount_color font-bold">
                                {{ teamInfo.user?.total_income }}
                            </span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-[12px] text-center font-bold">{{ $t("今日团队收入") }}</span>
                            <span class="text-[14px] text-amount_color font-bold">
                                {{ teamInfo.user?.today_income }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-[20px]">
                    <div class="flex items-center">
                        <div class="bg-[#FF6F01] w-[5px] h-[15px] rounded-[3px]"></div>
                        <div class="text-[12px] pl-[16px] font-semibold relative">{{ $t("您的收益结构") }}</div>
                    </div>

                    <div class="flex justify-center w-full pb-[10px] relative">
                        <div ref="main" class="w-[500px] h-[320px] main"></div>
                        <div class=" absolute top-[250px] right-[0]" style="max-width: var(--maxWidth);">
                            <div class="w-[64px] h-[64px] mr-[10px]" @click="openTurnTable">
                                <img :src="$getImageUrl('icon_wheel_home.png')" alt="">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="mt-[30px] px-[12px]">
                    <div class="gradient-border-box-small px-[11px] py-[16px]">
                        <div class="flex gap-1 items-center" @click="show = true">
                            <!-- <van-image :src="$getImageUrl('upgrade/date.png')" class="w-[20px] h-[auto]"></van-image> -->
                            <div class="text-[15px] font-bold text-[#FF6F01]">{{ $t("选择查询日期") }}</div>
                        </div>
                        <div class="flex justify-between text-[12px] font-medium py-[15px] border-b border-[#FF5001]">
                            <div class="text-[#FFFFFF]">{{ $t("团队成员总数") }}：</div>
                            <div class=" text-amount_color">{{ teamInfo?.team?.summary?.total_count || 0 }}</div>
                        </div>
                        <div class="flex justify-between text-[16px] font-medium py-[15px] border-b border-[#FF5001]">
                            <div class="text-[#fff]">{{ $t("今日新增充值量") }}：</div>
                            <div class=" text-amount_color">{{ teamInfo?.team?.summary?.today_user_recharge_sum || 0 }}
                            </div>
                        </div>
                        <div class="flex justify-between text-[16px] font-medium py-[15px]">
                            <div class="text-[#fff]">{{ $t("今天新增的成员") }}：</div>
                            <div class=" text-amount_color">{{ teamInfo?.team?.summary?.today_user_recharge_count || 0
                                }}</div>
                        </div>
                    </div>

                    <div v-for="(item, index) in 3" :key="index" class=" gradient-border-box px-[11px] mt-[15px] py-[20px]">
                    <div class="flex justify-between pb-[20px] items-center">
                        <div class="text-[12px] font-bold" v-if="item == 1">{{ $t("直接伙伴数据") }}</div>
                        <div class="text-[12px] font-bold" v-else-if="item == 2">{{ $t("间接伙伴数据") }}</div>
                        <div class="text-[12px] font-bold" v-else>{{ $t("三级伙伴数据") }}</div>
                        <div class="flex items-center gap-1" @click="router.push(`/membershiplist/${index + 1}`)">
                            <div class="text-[12px]">{{ $t("会员名单") }}</div>
                            <van-image :src="$getImageUrl('upgrade/arrow2.png')" class="w-[13px] h-[13px]"></van-image>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 pb-[16px] items-center">
                        <div class="flex flex-col items-center">
                            <span class=" text-[#FF6F01] text-[12px]">{{ $t("总人数") }}</span>
                            <span class="light:text-[#FFFFFF] text-[12px] font-bold">{{
                                teamInfo.team?.[TEAMLEVELENUM[index + 1]]?.count || 0 }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-[#FF6F01] text-[12px]">{{ $t("活跃人数") }}</span>
                            <span class="text-[#FFFFFF] text-[12px] font-bold">{{
                                teamInfo.team?.[TEAMLEVELENUM[index + 1]]?.active_count || 0 }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-[#FF6F01] text-[12px]">{{ $t("团队余额") }}</span>
                            <span class="text-[#FFFFFF] text-[12px] font-bold">{{
                                teamInfo.team?.[TEAMLEVELENUM[index + 1]]?.total_balance || 0 }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-[#FF6F01] text-[12px]">{{ $t("总回报") }}</span>
                            <span class="text-[#FFFFFF] text-[12px] font-bold">{{
                                teamInfo.team?.[TEAMLEVELENUM[index + 1]]?.total_brokerage || 0 }}</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <span class="text-[#FF6F01] text-[12px]">{{ $t("今日收益") }}</span>
                            <span class="text-[#FFFFFF] text-[12px] font-bold">{{
                                teamInfo.team?.[TEAMLEVELENUM[index + 1]]?.today_brokerage || 0 }}</span>
                        </div>
                    </div>
                </div>
                </div>

                
            </div>
        </div>
        <van-calendar @confirm="confirmDate" :confirm-disabled-text="$t('确认')" :title="$t('日期选择')"
            :confirm-text="$t('确认')" v-model:show="show" type="range" color="#75FA59" :formatter="formatter"
            :show-mark="false" switch-mode="year-month" @panel-change="panelChange">
            <template #subtitle>
                {{ calendarSubtitle }}
            </template>
        </van-calendar>

        <van-overlay :show="showloading">
            <van-loading type="spinner" class="loadingtext" />
        </van-overlay>
        <!-- 转盘游戏 -->
        <!-- <van-popup v-model:show="showGame" style=" width: 100%; background-color: #FFFFFF00;">
            <CustomWheelGame @close="showGame = false" />
        </van-popup> -->
    </div>
</template>

<script setup>
import CustomWheelGame from "@/components/CustomWheelGame.vue"
import GeneralNavBar from '@/components/GeneralNavBar.vue'
import { nextTick, onMounted, onUnmounted, ref, reactive } from 'vue';
import * as echarts from 'echarts';
import router from '../router';
import { TeamService } from '@/api/team';
import { useI18n } from 'vue-i18n';
import { Locale } from 'vant';
import { sysCurrency } from "@/utils/global"
import enUS from 'vant/es/locale/lang/en-US';
Locale.use('en-US', enUS);

const showGame = ref(false);
const openTurnTable = () => {
    // showGame.value = true
    router.push('/turntable');
}

const { t } = useI18n();
const TEAMLEVELENUM = Object.freeze({
    1: 'one',
    2: 'two',
    3: 'three',
})
const showloading = ref(true)
const pageInfo = reactive({
    start_date: "",
    end_date: "",
})
const show = ref(false)
const formatDate = (date) => `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;

const formatter = (day) => {
    const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
    if (day.type === 'start') {
        day.bottomInfo = t('开始');
    } else if (day.type === 'end') {
        day.bottomInfo = t('结束');
    }
    return day;
}

const confirmDate = (date) => {
    const [start, end] = date;
    show.value = false
    pageInfo.start_date = formatDate(start)
    pageInfo.end_date = formatDate(end)
    showloading.value = true
    fetchTeamInfo()

}
const formatterDateSub = (date) => {
    return `${date.getMonth() + 1}/${date.getFullYear()}`
}
const calendarSubtitle = ref(formatterDateSub(new Date()))
const panelChange = (date) => {
    calendarSubtitle.value = formatterDateSub(date.date)
}
const isSingNoZero = ref(false)
const teamInfo = ref({})
const fetchTeamInfo = async () => {
    const response = await TeamService.teamInfo({ ...pageInfo })
    showloading.value = false
    teamInfo.value = response
    const data = [
        { value: response.team_statistics?.total_ai_income, name: t('AI智能托管收益') },
        { value: response.team_statistics?.total_brokerage_income, name: t('代理团队佣金奖励') },
        { value: response.team_statistics?.total_invite_income, name: t('推广及其他奖励') },
        { value: response.team_statistics?.total_invest_income, name: t('个人库存利润') },
    ]
    isSingNoZero.value = data.filter(item => item.value > 0).length === 1
    nextTick(() => {
        initChart(data);
    })
}


const main = ref(null);
let myChart = null
const initChart = (data) => {
    if (main.value) {
        myChart = echarts.init(main.value);
        myChart.setOption(getchart(data));
    }
}
const getchart = (data) => {
    const option = {
        color: ['#C60CA1', '#00FF30', 'blue', '#FFD700'],
        /*  title: {
             text: 'Nightingale Chart',
             subtext: 'Fake Data',
             left: 'center'
         }, */
        tooltip: {
            trigger: 'item',
            // formatter: '{a} <br/>{b} : {c} ({d}%)',
            formatter: function (params) {
                const { name, value, percent } = params;
                return `<div style="font-size: 14px;color: #3158FF;">
                    <strong>${name}</strong><br>
                    ${t("数值")}: ${value}<br>
                    ${t("占比")}: ${percent}%
                </div>`
            },
            confine: true, // 强制 tooltip 显示在容器内
        },
        legend: {
            left: 'center',
            top: 'bottom',
            orient: 'vertical',
            data: [
                t('AI智能托管收益'),
                t('代理团队佣金奖励'),
                t('推广及其他奖励'),
                t('个人库存利润'),
            ],
            textStyle: {
                color: '#fff'
            },
            left: 0,
        },
        toolbox: {
            show: false,
            feature: {
                mark: { show: true },
                dataView: { show: true, readOnly: false },
                restore: { show: true },
                saveAsImage: { show: true }
            }
        },
        series: [
            {
                name: t('您的收益结构'),
                type: 'pie',
                radius: [30, 100],
                center: ['50%', '35%'],
                avoidLabelOverlap: true,
                padAngle: 0,//isSingNoZero.value ? 0 : 1,
                itemStyle: {
                    borderRadius: 5
                },
                label: {
                    show: false,
                },
                emphasis: {
                    label: {
                        show: false
                    }
                },
                data: data
            },
        ]
    };
    return option;
}


const showPopover = ref(false)

const resizeChart = () => {
    if (myChart) {
        myChart.resize();
    }
};
onMounted(() => {
    fetchTeamInfo()
    window.addEventListener('resize', resizeChart);
})
onUnmounted(() => {
    window.removeEventListener('resize', resizeChart);
    if (myChart) {
        myChart.dispose();
        myChart = null;
    }
});
</script>

<style lang="less" scoped>
.content {
    background: url("../assets/images/upgrade/team_bg.png") no-repeat top center;
    background-size: var(--maxWidth) auto;
    min-height: 100vh;
    background-attachment: fixed;
    /*  */
}

.released {
    background: #15131E;
    border-radius: 5px;
    padding: 23px 11px;
}

.detail {
    height: 30px;
    background: linear-gradient(95deg, #FDC2D8, #FCA65E, #FF7983, #041282);
    border-radius: 6px;
    border: none;
}

.title {
    color: #0937AD;
    // background: linear-gradient(275deg, #FED604 0%, #846BDE 100%);
    // ;
    // -webkit-background-clip: text;
    // -webkit-text-fill-color: transparent;
    // background-clip: text;
}

.numbers_content {
    background: linear-gradient(0deg, #16187A, #1333DA);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
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

:deep(.van-calendar__header .van-calendar__week) {
    display: none !important;
}

.main {
    div {
        white-space: wrap !important;
    }
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.gradient-border-box {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background-color: rgba(80, 37, 6, 0.35);
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
.gradient-border-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    /* 渐变背景作为边框 */
    background: linear-gradient(to right, #FF5001, #FFA201, #FF5001);
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

.gradient-border-box-small {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background-color: rgba(34, 37, 40, 0.7);
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
.gradient-border-box-small::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    /* 渐变背景作为边框 */
    background: linear-gradient(to right, #FF5001, #FFA201, #FF5001);
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