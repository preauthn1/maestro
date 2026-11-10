<template>
    <div class="">
        <van-nav-bar left-arrow :title="$t('交易记录')" @click-left="router.go(-1)">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('交易记录') }}</span>
            </template>
        </van-nav-bar>
        <div class="px-[16px]">
            <div class=" ">
                <Tabs v-model="activeTab" class="gap-0 justify-around" :tabList="tabList" @tabChange="onChange" />
            </div>
            <van-list v-model:loading="loading" :finished="finished" :loading-text="$t('加载中')"
                :finished-text="$t('没有更多了')" @load="onLoad">
                <div class="mt-[18px] mb-[30px] bg-white rounded-[10px]" v-for="item in projectList" :key="item.id">
                    <div class="">
                        <div class="w-full flex">
                            <span class=" bg-[#FF5C02] px-4 py-[5px] text-[12px] font-medium  jinxabs text-white">{{
                                item?.investment_product_name}}</span>
                        </div>
                        <div class="flex justify-between mt-[10px] px-[16px] mb-[8px] ">
                            <div class="text-black text-[12px] font-medium">{{ $t("收益率") }}:</div>
                            <div class="text-[#FF5C02] text-[12px] font-bold">{{ item.profit }}%</div>
                        </div>
                        <!-- <div class="flex justify-between px-[16px] mb-[8px]">
                            <div class="text-black text-[12px] font-medium">{{ $t("托管周期") }}:</div>
                            <div class="text-[#3158FF] text-[12px] font-bold">{{ item?.cycle }}h</div>
                        </div> -->
                        <div class="flex justify-between px-[16px] mb-[8px]">
                            <div class="text-black text-[12px] font-medium">{{ $t("托管金额") }}:</div>
                            <div class="text-[#00FF30] text-[12px] font-bold">{{ $sysCurrency }} {{ item.money }}</div>
                        </div>
                        <div class="flex justify-between px-[16px] mb-[8px]">
                            <div class="text-black text-[12px] font-medium">{{ $t("托管收益") }}:</div>
                            <div class="text-[#FF5C02] text-[12px] font-bold">{{ $sysCurrency
                                }} {{ item.profit_money }}</div>
                        </div>
                        <div class="flex justify-between px-[16px] mb-[8px]">
                            <div class="text-black text-[12px] font-medium">{{ $t("开始时间") }}:</div>
                            <div class="text-black text-[12px] font-bold">{{ item.start_time }}</div>
                        </div>
                        <div class="flex justify-between px-[16px] mb-[8px]">
                            <div class="text-black text-[12px] font-medium">{{ $t("结束时间") }}:</div>
                            <div class="text-black text-[12px] font-bold">{{ item.end_time }}</div>
                        </div>
                        <div class="text-black px-[16px] text-[12px] font-medium"
                            :class="activeTab == 1 ? 'pb-[16px]' : ''">
                            <span class="text-black text-[12px] font-medium pr-2">{{ $t('计划详情') }}:</span>
                            <span class="text-[12px] text-black font-medium">{{ item?.description }}</span>
                        </div>
                        <div class="px-[32px] my-[8px] pb-[16px]" v-if="activeTab == 0">
                            <van-button class="w-full bg-style rounded-[6px]">
                                <span class="text-[#FFB79D] text-[12px]">{{ $t("托管进行中") }}...</span>
                            </van-button>
                        </div>
                    </div>
                </div>
            </van-list>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue';
import { useRouter } from "vue-router";
import Tabs from "@/components/Tabs.vue"
import { AuthService } from '@/api/home';

const router = useRouter()
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
    const res = await AuthService.alorder_list({ ...pageInfo, "status": activeTab.value == 0 ? 2 : 3 });
    loading.value = false;
    projectList.value = [...projectList.value, ...res.data]
    if (res.data.length >= 10) {
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


</script>

<style lang="less" scoped>
.recordlist-title-linear {
    background: #0937AD;
    border-radius: 0px 0px 20px 20px;
    text-align: center;
}

.bg-style {
    background: linear-gradient( 89deg, #FF4901 0%, #FF7801 100%);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
}

.jinxabs {
    border-radius: 10px 0px 15px 0px;
}
</style>
