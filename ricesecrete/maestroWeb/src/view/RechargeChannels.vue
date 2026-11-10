<template>
    <div class="">
        <van-nav-bar  @click-left="router.go(-1)" >
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('储值选择') }}</span>
            </template>
        </van-nav-bar>
        <div v-for="(item, index) in depositType" :key="index" @click="jumpdetail(item)"
            class="mx-[11px] gradient-border-box-fixed-leiji mb-[16px]">
            <div class="flex justify-between">
                <van-image class="mt-[10px] mb-[10px] ml-[10px]" width="32" height="32" :src="item.image" round
                    fit="cover" />
                <div class="flex justify-between flex-1 items-center ml-[10px] mr-[10px]">
                    <span class="text-[13px]">{{ item.name }}</span>
                    <van-icon name="arrow" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Tabs from "@/components/Tabs.vue"
import { ref, reactive, onMounted } from "vue";
import { useRouter,useRoute } from "vue-router";
import { AuthService } from "@/api/home";
const router = useRouter()
const route = useRoute()
const depositType = ref([])
const depositMethod = async () => {
    const res = await AuthService.recharge();
    depositType.value = res
}

// const getWidType = async () => {
//     const res = await AuthService.withdrawals()
//     depositType.value = res.bank_platforms
// }

const jumpdetail = (item) => {
        router.push(`/rechargedetail?activity_id=${route.query.activity_id ? route.query.activity_id : ""}&recharge_bonus_id=${route.query.recharge_bonus_id ? route.query.recharge_bonus_id : ""}&content=${encodeURIComponent(JSON.stringify(item))}`)
}
onMounted(() => {
    
    depositMethod()
        
    
})
</script>

<style scoped>

.item {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    border: 1px solid #FFFFFF;
}
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