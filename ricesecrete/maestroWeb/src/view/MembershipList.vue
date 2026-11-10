<template>
    <div class="">
        <van-nav-bar left-arrow @click-left="router.go(-1)">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold ">{{ $t('会员名单') }}</span>
            </template>
        </van-nav-bar>
        <div class="mt-[20] ml-[16px] mr-[16px] mb-[30px]">
            <div v-if="memberList.length"  v-for="(item, index) in memberList" :key="index"
                class="gradient-border-box-fixed rounded-[5px] flex justify-between items-center mb-[12px]">
                <van-image class="ml-[7px] mt-[13px] mb-[13px]" width="40" height="40" fit="cover" round
                    :src="item.avatar" />
                <div class="flex-1 ml-[12px] flex justify-between items-center">
                    <div>
                        <span class="text-[12px] font-medium">{{ item?.user_name }}</span>
                        <div class="flex">
                            <span class="text-[12px] font-medium">{{ $t("直属下属") }}：</span>
                            <span class="text-[12px] font-extrabold text-[#FF5001]">{{ item.one_count }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col mr-[8px]">
                        <span class="text-[12px] font-medium">{{ $t("注册日期") }}：</span>
                        <span class="text-[12px] font-medium">{{ item?.create_time }}</span>
                    </div>
                </div>
            </div>
            <!--  -->
            <div v-else  class=" pt-[132px] text-center">
                <img class=" w-[158px] mx-auto" :src="$getImageUrl('empty_img.png')" alt="">
                <div class=" mt-[62px] text-[#959595]">{{ $t("暂无数据") }}</div>
            </div>
        </div>
    </div>

</template>

<script setup>
import { TeamService } from '@/api/team';
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
const router = useRouter()
const route = useRoute()

const memberList = ref([])
const fetchTeamDetails = async () => {
    const response = await TeamService.teamDetail({ level: route.params.id })
    console.log(response, '---response');
    memberList.value = response
}
onMounted(() => {
    fetchTeamDetails()
})
</script>

<style lang="less" scoped>
.gradient-border-box-fixed {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background: rgba(34,37,40,0.7);
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


</style>