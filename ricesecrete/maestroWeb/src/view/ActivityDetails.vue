<template>
    <div>
        <van-nav-bar left-arrow @click-left="router.go(-1)">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{$t('活动详情')}}</span>
            </template>
        </van-nav-bar>
        <div class="px-[22px]">
            <div class="gradient-border-box-fixed-leiji mb-[15px] " v-for="(item,index) in detail">
                <div class="absolute top-0 left-0 bg-[#FF5001] px-[12px] rounded-br-[10px]">{{ index + 1 }}</div>
                <div class="p-[15px] relative z-[2]">
                    <div class="grid grid-cols-2 gap-4 ">
                        <div class="flex flex-col gap-2 justify-end items-center">
                            <div>{{ $t('入金金额') }}</div>
                            <span class="text-[#15CF00] text-[13px] font-bold">{{ item?.recharge_amount
                            }}</span>
                        </div>
                        <div class="flex flex-col gap-2 justify-center items-center mt-[10px]">
                            <div>{{ $t('活动奖励') }}</div>
                            <span class="text-[#15CF00] text-[13px] font-bold">{{ item?.reward }}</span>
                        </div>
                    </div>
                    <div class="w-full px-[20px] py-[15px]">
                        <van-button type="primary" :disabled="item?.reward_value <= 0" class="mt-[10px] w-full"
                            @click="get_activity(item)">{{ $t('领取奖励')
                            }}</van-button>
                    </div>
                    <div style="border: 1px solid #FF5001;"></div>
                    <div class="mt-[20px]">
                        <div class="flex justify-between items-center">
                            <div class="flex gap-2 flex-col items-center">
                                <div>{{ $t('活动金额门槛') }}</div>
                                <span class="text-[#FFFB00] shrink-0 text-[13px] font-bold">{{ item?.amount
                                }}</span>
                            </div>
                            <div class="flex gap-2  flex-col items-center w-1/2">
                                <div>{{ $t('活动赠送比例') }}</div>
                                <span class="text-[#FFFB00] shrink-0 text-[13px] font-bold">{{ item?.rate }}</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-[20px]">
                            <div class="flex gap-2 flex-col items-center">
                                <div>{{ $t('活动名额') }}</div>
                                <span class="text-[#FFFB00] shrink-0 text-[13px] font-bold">{{ item?.remark }}</span>
                            </div>
                            <div class="flex gap-2  flex-col items-center">
                                <div>{{ $t('活动截止时间') }}</div>
                                <span class="text-[#FFFB00] shrink-0 text-[13px] font-bold">{{ item?.end_time }}</span>
                            </div>
                        </div>

                        <!-- <div class="flex flex-col px-[11px] mt-[20px]">
                            <div class="flex gap-2  justify-between items-center ">
                                <div>{{ $t('活动金额门槛') }}</div>
                                <span class="text-[#FFFB00] shrink-0 text-[13px] font-bold">{{ item?.amount
                                }}</span>
                            </div>
                            <div class="flex gap-2  justify-between items-center mt-[20px]">
                                <div>{{ $t('活动赠送比例') }}</div>
                                <span class="text-[#FFFB00] shrink-0 text-[13px] font-bold">{{ item?.rate }}</span>
                            </div>
                            <div class="flex gap-2  justify-between items-center mt-[20px]">
                                <div>{{ $t('活动名额') }}</div>
                                <span class="text-[#FFFB00] shrink-0 text-[13px] font-bold">{{ item?.remark }}</span>
                            </div>
                        </div> -->
                        <div class="w-full px-[20px] pt-[15px]">
                            <van-button type="primary"
                                style="background: linear-gradient( 89deg, #014DFF  0%, #01B7FF  100%), linear-gradient( 89deg, #014DFF 0%, #01B7FF 100%) !important; "
                                class="mt-[10px] w-full" @click="handle(item)">
                                <span class="text-[13px]">{{ $t('立即入金') }}</span>
                            </van-button>
                        </div>
                        <!-- <div class="flex gap-2  justify-between items-center mt-[20px]">
                            <div>{{ $t('活动时间') }}：</div>
                            <span class="text-[#FFFB00] shrink-0 text-[13px] font-bold">{{ item?.end_time }}</span>
                        </div> -->
                        <!-- <div class="pt-[10px] px-[11px] text-[12px] font-medium">
                            {{ $t('活动时间') }}：
                        </div>
                        <div class="text-[#FFFB00] px-[11px] text-[12px] mt-[10px] font-medium  w-full">
                            {{ item?.end_time }}
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { showToast } from 'vant'
import { onMounted, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { TeamService } from '@/api/team'

const { t } = useI18n()
const router = useRouter()
const route = useRoute()
//领取活动奖励
const get_activity = async (item) => {
    if (item.reward_value <= 0) return
    const response = await TeamService.get_activity_reward({ id:route.query.id, recharge_bonus_id: item.id })
    get_details()
    showToast(t('领取成功'))
}

//活动详情
const detail = ref([])
const get_details = async () => {
    const res = await TeamService.activity_detail({ activity_id: route.query.id })
    detail.value = res
}
//立即入金
const handle = async (item) => {
    const res = await TeamService.recharge_now({ activity_id: route.query.id, recharge_bonus_id: item.id })
    if(res.code){
        showToast(res.msg)
        return router.replace('/activity')
        
    }else{
        router.push(`/rechargechannels?recharge_bonus_id=${item.id}&activity_id=${route.query.id}`)
    }
    
}
onMounted(() => {
    if (route.query.id) {
        get_details()
    } else {
        router.replace('/')
    }
})

</script>

<style lang="less" scoped>
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
    filter: blur(0.2px);
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
    z-index: 0;
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