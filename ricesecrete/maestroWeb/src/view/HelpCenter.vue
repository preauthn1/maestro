<template>
    <div class="">
        <div class="">
            <van-nav-bar left-arrow :title="route.query.type == 1 ? $t('关于我们') : $t('帮助中心')" @click-left="router.go(-1)">
                <template #left>
                    <van-icon name="arrow-left" color="#FF5001" />
                </template>
                <template #title>
                    <span class="text-[11px] font-semibold">{{ route.query.type == 1 ? $t('关于我们') : $t('帮助中心') }}</span>
                </template>
            
            </van-nav-bar>
            <div v-if="!hasData" class=" pt-[132px] text-center">
                <img class=" w-[158px] mx-auto" :src="$getImageUrl('empty_img.png')" alt="">
                <div class=" mt-[62px] text-[#959595]">{{ $t("暂无数据") }}</div>
            </div>
            <van-list v-model:loading="loading" :finished="finished" :loading-text="$t('加载中')"
                :finished-text="$t('没有更多了')" @load="onLoad" v-else>
                <div @click="toHelpDetails(item?.id)" v-for="(item, index) in list" :key="index"
                    class="ml-[16px] mr-[16px] gradient-border-box-fixed-leiji rounded-[5px] mt-[16px]">
                    <div class="flex justify-between ml-[12px] mr-[12px] pt-[20px] pb-[20px] items-center">
                        <span class="text-[14px] font-semibold">{{ item?.title }}</span>
                        <van-icon name="arrow" />
                    </div>
                </div>
            </van-list>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import { useRouter,useRoute } from "vue-router";
import { AuthService } from "@/api/home";
const router = useRouter()
const route = useRoute();
const hasData = ref(true);
const list = ref([])
const loading = ref(false)
const finished = ref(false)
const pageInfo = reactive({
    page: 1,
    limit: 10,
})
const toHelpDetails = (id) => {
    if(route.query.type == 1){
        router.push(`about?id=${id}`)
    }else{
        router.push(`helpdetail?id=${id}`)
    }   
    
}
const onLoad = async () => {
    if(!route.query.type){
        return router.back();
    }
    loading.value = true
    const res = await AuthService.article({ ...pageInfo, type: route.query.type })
    list.value = [...list.value, ...res.data]
    if (list.value.length < res.total) {
        return pageInfo.page++
    }
    finished.value = true
}

</script>

<style lang="less" scoped>


.gradient-border-box-fixed-leiji {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
   background: rgba(80,37,6,0.35);
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