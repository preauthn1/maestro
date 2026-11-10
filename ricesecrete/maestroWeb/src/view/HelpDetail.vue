<template>
    <div class="  min-h-[100vh] pb-[20px]">
        <van-nav-bar left-arrow :title="$t('帮助详情')" @click-left="router.go(-1)">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('帮助详情') }}</span>
            </template>
        </van-nav-bar>
        <div class="content-background px-[15px]">
            <div class="gradient-border-box-fixed-leiji py-[20px] px-[16px]" v-html="noticeDetail?.content"></div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { AuthService } from "@/api/home";
const router = useRouter()
const route = useRoute()
const noticeDetail = ref()

onMounted(async () => {
    const res = await AuthService.article_details({ id: route.query.id })
    noticeDetail.value = res
})
</script>

<style scoped>
.notice_rich {
    background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
    padding: 20px;
}

.notice_rich>img {
    max-width: 100%;
    height: auto;
    object-fit: cover;
}

:deep(p) {
    color: #FFFFFF;
}

:deep(strong) {
    color: #FFFFFF;
}

:deep(span) {
    color: #FFFFFF !important;
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