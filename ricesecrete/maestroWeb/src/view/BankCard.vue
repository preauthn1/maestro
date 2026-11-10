<template>
    <div class="content min-h-[100vh]">
        <van-nav-bar left-arrow :title="$t('银行卡')" @click-left="toBack()">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('银行卡') }}</span>
            </template>
        </van-nav-bar>
        <!--  -->
        <div class="mx-[11px] " v-if="!store?.userInfo?.user_info?.bank_name">
            <div class="flex items-center">
                <div class="heng"></div>
                <span class="text-[12px] font-bold">{{ $t("已绑定的银行卡") }}</span>
            </div>

            <div class="mt-[14px]  gradient-border-box-fixed-leiji">
                <div class="mx-[16px] pt-[57px] pb-[57px]">
                    <van-button @click="router.push('/editbankcard')" class="rounded-[6px]"
                        style="background: linear-gradient( 89deg, #FF4901 0%, #FF7801 100%);height: 44px;" block
                        type="default" icon="plus">
                        <span class="text-[15px] font-bold">{{ $t("添加银行卡") }}</span>
                        <template #icon>
                            <div class="mr-[10px]">
                                <img :src="$getImageUrl('btn-icon.png')" class="w-[20px] h-[20px]">
                            </div>
                        </template>
                    </van-button>
                </div>
            </div>
        </div>
        <div class="mx-[11px]" v-else>
            <div class="flex items-center">
                <div class="heng"></div>
                <span class="text-[12px] font-bold">{{ $t("已绑定的银行卡") }}</span>
            </div>

            <div class="mt-[14px] gradient-border-box-fixed-leiji">
                <div class="p-[14px] pb-[14px]">
                    <div class="back-crad-content flex flex-col">
                        <span class="text-[17px] font-semibold pt-[4px] ml-[10px]">{{
                            store.userInfo?.user_info?.bank_name }}</span>
                        <!-- <div class="flex items-center gap-3">
                                <span class="text-[20px] font-medium ml-[10px] mt-[5px]" v-show="!showName">{{
                                    (store.userInfo?.user_info?.bank_number).slice(0, 4) }} **** ****
                                    {{ (store.userInfo?.user_info?.bank_number).slice(-4) }}</span>
                                <span
                                    class="text-[20px] font-medium ml-[10px]  w-[80%] overflow-hidden text-ellipsis whitespace-nowrap"
                                    v-show="showName">{{
                                        (store.userInfo?.user_info?.bank_number) }}</span>
                                <van-icon @click="showName = !showName" class=""
                                    :name="showName ? 'eye-o' : 'closed-eye'" size="16" />
                            </div> -->
                        <span class="text-[14px]  pt-[8px] ml-[10px] font-semibold">{{
                            store?.userInfo?.user_info?.document_type
                            }}：{{
                                store?.userInfo?.user_info?.document_id }}</span>
                        <!-- <span class="text-[17px]  pt-[8px] ml-[10px]">1200000</span> -->
                        <span
                            class="text-[14px] font-semibold ml-[10px] mt-[8px] mb-[16px] w-[80%] text-ellipsis whitespace-nowrap overflow-hidden">{{
                                $t("持卡人") }}：{{
                                store.userInfo?.user_info?.true_name }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useUserStore } from '../store/userinfo';
const store = useUserStore();
const router = useRouter()
const showName = ref(false)
const isHasCard = ref(false);
const route = useRoute()
function toBack() {
    if (route.query.cate == 1) {
        router.go(-2);
    } else {
        router.go(-1);
    }
}
</script>

<style scoped>
.back-crad-content {
    width: 100%;
    height: auto;
    background: url("@/assets/images/icon_back_card.png") no-repeat center;
    background-size: cover;
    border-radius: 10px;
}

.bank-card-background {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 18px;
    border: 1px solid #FFFFFF;
}

.content {
    background: url("../assets/images/home/home_bg.png") no-repeat top center;
    background-size: var(--maxWidth) auto;
    background-attachment: fixed;
}

.van-button--default {
    border: none !important;
    border-radius: 13px !important;
}

.gradient-border-box-fixed-leiji {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background: rgba(34, 37, 40, 0.7);
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