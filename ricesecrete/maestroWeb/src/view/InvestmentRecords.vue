<template>
    <div class="">
        <van-nav-bar left-arrow :title="$t('购买记录')" @click-left="router.go(-1)">
            <template #left>
                    <van-icon name="arrow-left" color="#FF5001" />
                </template>
                <template #title>
                    <span class="text-[11px] font-semibold">{{ $t('购买记录')}}</span>
                </template>
        </van-nav-bar>
        <div class="mx-[11px]">
            <div class="flex flex-col text-center pt-[20px] gradient-border-box-fixed">
                <span class="text-[11px] font-medium">{{ $t("总收益") }}</span>
                <span class="record-text-linear text-[#00FF30]">{{ count?.total_invest_income }}</span>
                <div class="pt-[24px] pl-[10px] pr-[10px] pb-[16px] flex justify-between">
                    <div class="flex flex-col items-center">
                        <span class="text-[11px] font-medium">{{ $t("昨日收益") }}</span>
                        <span class="font-bold text-[11px] text-[#FFFB00]">{{ count?.yesterday_order_income }}</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[11px] font-medium">{{ $t("总购买次数") }}</span>
                        <span class="font-bold text-[11px] text-[#FFFB00]">{{ count?.order_num }}</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-[11px] font-medium">{{ $t("总购买金额") }}</span>
                        <span class="font-bold text-[11px] text-[#FFFB00]">{{ count?.order_money }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-[20px] px-[11px] flex items-center">
            <div class="heng"></div>
            <span class="text-[12px] font-semibold">{{ $t("购买订单") }}</span>
        </div>
        <div class=" mt-[20px] px-[11px] ">
            <Tabs v-model="activeTab" class="flex justify-around"  :tabList="tabList" @tabChange="onChange" />
        </div>
        <van-list v-model:loading="loading" :finished="finished" :loading-text="$t('加载中')" :finished-text="$t('没有更多了')"
            @load="onLoad">
            <!--  -->
            <div class="mt-[18px] ml-[16px] mr-[16px] mb-[30px]" v-for="item in projectList" :key="item.id">
                <div class="content">
                    <div class="flex">
                        <span class=" bg-[#FF5C02] px-4 py-1 text-center jinxabs text-white">{{$t("广告库存")}}</span>
                    </div>
                    <div class="px-10 pb-4">
                        <div class="flex justify-between items-start  mt-[10px]">
                            <div class="text-[12px] text-black font-medium">
                                <span class="text-[12px] text-black font-medium whitespace-nowrap">{{ $t('订单交易ID') }}:</span>
                            </div>
                            <div class="text-[12px] text-black font-medium text-right">
                                <span class="text-[12px] text-black font-medium text-right break-all">{{item?.order_no}}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-start mt-[10px]">
                            <div class="text-[12px] text-black font-medium">{{ $t('预计收益') }}:</div>
                            <div class="text-[12px] text-black font-medium">{{item?.profit_money}}</div>
                        </div>
                        <div class="flex justify-between items-start mt-[10px]">
                            <div class="text-[12px] text-black font-medium">{{ $t('投放周期') }}:</div>
                            <div class="text-[12px] text-[#FF5C02] font-medium text-right">{{item?.cycle_str}}</div>
                        </div>
                        <div class="flex justify-between items-start mt-[10px]">
                            <div class="text-[12px] text-[black] font-medium">{{ $t('库存价格') }}:</div>
                            <div class="text-[12px] text-[#FF5C02] font-medium text-right">{{ item?.money }} </div>
                        </div>
                        <div class="flex justify-between items-start mt-[10px]" v-for="attach in item.other_info">
                            <div class="text-[12px] text-[black] font-medium whitespace-nowrap">{{ attach.title }}:</div>
                            <div class="text-[12px] text-[#FF5C02] font-medium text-right">{{attach.content}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </van-list>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from "vue-router";
import Tabs from "@/components/Tabs.vue"
import { AuthService } from '@/api/home';

const router = useRouter()
const count = ref()
const projectList = ref([])
const pageInfo = reactive({
    page: 1,
    limit: 10,
})
const loading = ref(false)
const finished = ref(false)
const tabList = ['进行中', '已完成']
const activeTab = ref(0)
const onLoad = async () => {
    loading.value = true;
    const res = await AuthService.order_list({ ...pageInfo, "status": activeTab.value == 0 ? 2 : 3 });
    loading.value = false;
    projectList.value = [...projectList.value, ...res.data]
    if (projectList.value.length < res.total) {
        pageInfo.page++
        return;
    }
    finished.value = true;
}
const onChange = (value) => {
    finished.value = false;
    pageInfo.page = 1;
    projectList.value = []
    onLoad();
};
onMounted(async () => {
    const res = await AuthService.order_count()
    count.value = res
})
</script>

<style scoped>



.record-text-linear {
    margin-top: 10px;
    font-weight: 700;
    font-size: 18px;
    line-height: 18px;
}

.recordlist-title-linear {
    background: #0937AD;
    border-radius: 0px 0px 20px 20px;
    text-align: center;
}
.jinxabs {
    border-radius: 10px 0px 15px 0px;
}
.content {
    background: #FFFFFF;
    border: 1px solid #FF5C02;
    border-radius: 10px;
}

.gradient-border-box-fixed {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background-color: rgba(80,37,6,0.35);
    /* 白色，60% 透明度 */
    border-radius: 10px;
    /* **重要：保持和伪元素一致的圆角** */
    overflow: hidden;
    /* 确保内容不会溢出圆角 */
    /* Optional: 添加一个毛玻璃效果 (现代浏览器) */
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
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
</style>
