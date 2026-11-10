<template>
    <div class="">
        <van-nav-bar @click-left="router.go(-1)" @click-right="router.push('/profitrules')">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('AI智能交易') }}</span>
            </template>
            <template #right>
                <span class="text-[11px] text-[#FF5001] font-semibold">{{ $t('规则') }}</span>
            </template>
        </van-nav-bar>
        <div class="px-[11px] pb-[30px]">


            <div class="gradient-border-box-fixed-leiji">
                <div class="flex justify-between p-4 py-6">
                    <div class="flex flex-col justify-center items-center w-1/2 border-r border-[#FF5001]">
                        <div class="text-[14px] font-medium">{{ $t("昨日收益") }}</div>
                        <div class="text-[#00FF30] font-bold mt-[6px] text-[14px]">{{ detail?.yesterday_profit }}</div>
                    </div>
                    <div class="flex flex-col justify-center items-center w-1/2">
                        <div class="text-[14px] font-medium">{{ $t("累计收益") }}</div>
                        <div class="text-[#00FF30] font-bold mt-[6px] text-[14px]">{{ detail?.total_profit }}</div>
                    </div>
                </div>
            </div>
            <div class="gradient-border-box-fixed p-4 px-[33px] flex justify-between  my-4 items-center"
                @click="router.push('/escrowrecords')">
                <div class="text-[12px] font-bold text-[#FF5101]">{{ $t("交易记录") }}</div>
                <van-icon name="arrow" size="20" color="#FF5101"></van-icon>
            </div>
            <div class="flex items-center">
                <div class="heng"></div>
                <div class=" text-[12px] font-semibold">{{ $t("IA交易模式") }}</div>
            </div>

            <div class="mt-[15px] bg-white rounded-[10px] relative " v-for="item, index in content" :key="index">
                <div class="w-full flex">
                    <span class=" bg-[#FF5C02] px-4 py-[5px] text-[12px] font-medium  jinxabs text-white">{{
                        item?.product_name
                        }}</span>
                </div>
                <div class="mt-[20px] py-4 px-[15px]">
                    <div class="flex justify-between mb-[6px]">
                        <div class=" text-[12px] font-medium text-black">{{ $t('收益率') }}:</div>
                        <div class="text-[12px] text-[#FF5C02] font-bold">{{ item?.income_text }}</div>
                    </div>
                    <!-- <div class="flex justify-between mb-[6px]">
                        <div class=" text-[12px] font-medium text-black">{{ $t('托管周期') }}</div>
                        <div class="text-[12px] text-[#286EE0] font-bold">{{ item?.cycle }}h</div>
                    </div> -->
                    <div class="flex justify-between mb-[6px]">
                        <div class=" text-[12px] font-medium text-black">{{ $t('预计收益') }}:</div>
                        <div class="text-[12px] text-[#FF5C02] font-bold">{{ item?.income == "" ? '' : $sysCurrency }} {{ item?.income == "" ? '--' :
                            ((Number(item?.income)
                            * Number(item?.profit)) / 100).toFixed(2) }}
                        </div>
                    </div>
                    <div class="mb-[6px]">
                        <span class=" text-[12px] font-medium text-black pr-2">{{ $t('计划详情') }}:</span>
                        <span class="text-[12px] text-black font-medium">{{ item?.description }}</span>
                    </div>

                    <div class="mt-[20px] px-[30px]">
                        <van-field v-model="number" type="number" class="rounded-[6px]" :placeholder="$t('智能交易金额')"
                            v-if="!content.length > 0" />
                        <van-field v-model="item.income" maxlength="10" type="number" class="rounded-[6px]"
                            @input="handlefieid(item, $event)" :placeholder="$t('智能交易金额')" v-else />
                        <van-button class="w-full bg-[#007AFF]  rounded-[6px] text-[20px]"
                            style="background: linear-gradient( 89deg, #FF4901 0%, #FF7801 100%);"
                            @click="handlebuy(item.income, item.id)">
                            <span class="text-[13px] letter font-semibold">{{ $t("立即开始IA交易") }}</span>
                        </van-button>
                    </div>

                </div>
            </div>
            <!-- <van-calendar @confirm="confirmDate" :confirm-disabled-text="$t('确认')" :title="$t('日期选择')"
                :confirm-text="$t('确认')" v-model:show="show" type="range" color="#75FA59" :formatter="formatter"
                :show-mark="false" switch-mode="year-month" @panel-change="panelChange">
                <template #subtitle>
                    {{ calendarSubtitle }}
                </template>
            </van-calendar> -->
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { AuthService } from "@/api/home";
import { useI18n } from 'vue-i18n';
import { Locale, showToast } from 'vant';
import enUS from 'vant/es/locale/lang/en-US';
Locale.use('en-US', enUS);
const { t } = useI18n();
const router = useRouter()
const show = ref(false)
const content = ref()
const number = ref()
const pageInfo = reactive({
    page: 1,
    limit: 10,
    start_time: "",
    end_time: ""
})
const benefitscontent = ref()
const benefitsList = ref([])
const formatDate = (date) => `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`;
const handlefieid = (item, event) => {
    let value = event.target.value;

    // 只允许输入数字和小数点
    value = value.replace(/[^0-9.]/g, '');

    // 确保小数点只能出现一次
    if (value.indexOf('.') !== -1) {
        const parts = value.split('.');
        value = parts[0] + '.' + parts[1].slice(0, 2); // 小数点后最多保留两位
    }

    // 确保首位不能是多个0
    if (value.startsWith('0') && value[1] !== '.' && value.length > 1) {
        value = value.replace(/^0+/, '0');
    }

    // 更新 item.income
    item.income = value;
};

// const formatter = (day) => {
//     if (day.type === 'start') {
//         day.bottomInfo = t('开始');
//     } else if (day.type === 'end') {
//         day.bottomInfo = t('结束');
//     }
//     return day;
// }
const loading = ref(false);

const finished = ref(false)
const count = ref(0)
const confirmDate = (date) => {
    pageInfo.page = 1;
    benefitscontent.value = {};
    benefitsList.value = [];
    const [start, end] = date;
    show.value = false
    pageInfo.start_time = formatDate(start)
    pageInfo.end_time = formatDate(end)
    onLoad()

}
const handlebuy = async (amount, id) => {
    if (amount == '') {
        return showToast(t("智能交易金额"))
    }
    /*  if(amount < 1){
         return showToast(t("请输入托管金额"))
     } */
    const res = await AuthService.buy({ 'id': id, 'amount': amount });
    showToast(t("交易成功"))
}
const formatterDateSub = (date) => {
    return `${date.getMonth() + 1}/${date.getFullYear()}`
}
const calendarSubtitle = ref(formatterDateSub(new Date()))
const panelChange = (date) => {
    calendarSubtitle.value = formatterDateSub(date.date)
}
const onLoad = async () => {
    loading.value = true
    const res = await AuthService.getBalanceLog({ ...pageInfo })
    benefitscontent.value = res;
    loading.value = false
    if (res.list.length < 10) {
        finished.value = true
        benefitsList.value = [...benefitsList.value, ...res.list]
        return;
    }
    benefitsList.value = [...benefitsList.value, ...res.list]
    finished.value = true
    pageInfo.page++;
}
const detail = ref()
onMounted(async () => {
    const response = await AuthService.alorder_detail();
    detail.value = response
    const res = await AuthService.investment_product();
    content.value = res

})
</script>

<style scoped>
:deep(.van-nav-bar) {
    background: transparent !important;
}

.jinxabs {
    border-radius: 10px 0px 15px 0px;
}

/* .top-content {
    width: 100%;
    height: auto;
    background: url("@/assets/images/icon_yue_top.png") no-repeat center;
    background-size: 100% 100%;
} */
.top-text-linear {
    font-weight: 500;
    font-size: 12px;
    color: #FFFFFF;
    line-height: 12px;
    background: linear-gradient(180deg, #F4C5D7 0%, #F0A96B 33.10546875%, #EF8186 59.0576171875%, #03177D 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

:deep(.van-cell) {
    padding: 9px 11px !important;
}

:deep(.van-field__control) {
    color: #286EE0 !important;
}

:deep(.van-field__control::placeholder) {
    font-size: 12px;
    color: #286EE0 !important;
}

.recomd {
    background: linear-gradient(180deg, #FF7646, #E25625);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
}

.wrapper {
    position: relative;
    flex: 1;
    overflow: hidden
}

.van-popup {
    position: absolute
}

.van-popup--top {
    top: 315px;
}

.radio-highlight-color {
    color: #75FA59;
}

.inputBorder {
    border: 1px solid #75FA59;
    border-radius: 4px;
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
</style>