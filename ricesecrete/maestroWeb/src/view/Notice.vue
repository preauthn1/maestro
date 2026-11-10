<template>
    <div class="  min-h-[100vh]">
        <van-nav-bar left-arrow :title="$t('通知')" @click-left="router.go(-1)">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('通知') }}</span>
            </template>
        </van-nav-bar>
        <div class="px-[11px]">
            <div class="mb-[18px]">
                <tabs v-model="activeTab" :tabList="tabList" class="flex justify-around" @tabChange="handleTabChange" />
            </div>
            <van-list v-model:loading="loading" :finished="finished" :loading-text="$t('加载中')"
                :finished-text="$t('没有更多了')" @load="onLoad">
                <div @click="toNoticeDetails(item)" v-for="(item, index) in noticeList" :key="index"
                    class=" gradient-border-box-fixed-leiji mb-[11px] mx-[11px] px-[13px] p-[10px]">
                    <div class=" text-[14px] font-medium mb-[12px] text-white">{{ item?.title }}</div>
                    <div class="notice_content text-[11px] font-medium" v-html="item?.content"></div>
                    <div class=" mt-[15px] text-right text-[10px] text-white">{{ item?.create_time }}</div>
                </div>
            </van-list>
        </div>
    </div>
</template>

<script setup>
import tabs from "@/components/Tabs.vue"
import { ref, reactive, onMounted } from "vue";
import { useRouter } from "vue-router";
import { AuthService } from "@/api/home";
import { useSystemStore } from "../store/system";
const store = useSystemStore();
const router = useRouter()
const loading = ref(false)
const finished = ref(false)
const tabList = ['公告', '讯息']
const activeTab = ref(0)
const noticeList = ref([])
const pageInfo = reactive({
    page: 1,
    limit: 10,
})
const handleTabChange = () => {
    noticeList.value = []
    finished.value = false
    pageInfo.page = 1
    onLoad()
}
const onLoad = async (key) => {
    loading.value = true;
    store.notice = activeTab.value
    if (activeTab.value == 0) {
        const res = await AuthService.notice({ ...pageInfo });
        loading.value = false
        noticeList.value = [...noticeList.value, ...res.data]
        if (noticeList.value.length < res.total) {
            pageInfo.page++;
        } else {
            finished.value = true
        }
    } else {
        const res = await AuthService.message({ ...pageInfo });
        loading.value = false
        noticeList.value = [...noticeList.value, ...res.data]
        if (noticeList.value.length < res.total) {
            pageInfo.page++;
        } else {
            finished.value = true
        }

    }
}
const toNoticeDetails = (item) => {
    let strItem = JSON.stringify(item)
    const encodedParam = encodeURIComponent(strItem);
    router.push("noticeDetails?content=" + encodedParam)
}
onMounted(async () => {
    if (store.notice) {
        activeTab.value = store.notice
    }

})
</script>

<style lang="less" scoped>
.notice_items {
    background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
    padding: 10px;
}

.notice_content {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    overflow: hidden;
    text-overflow: ellipsis;
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