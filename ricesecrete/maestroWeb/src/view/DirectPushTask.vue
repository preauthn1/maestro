<template>
    <div class="min-h-[100vh]">
        <div class="pb-[20px]">
            <van-nav-bar left-arrow @click-left="router.go(-1)">
                <template #left>
                    <van-icon name="arrow-left" color="#FF6F01"></van-icon>
                </template>
                <template #title>
                    <div class="text-[11px] font-semibold">{{ $t("直接推广奖励") }}</div>
                </template>
            </van-nav-bar>
            <div class="px-[11px]">
                <div class="flex items-center justify-between pb-[20px]">
                    <div class="flex items-center">
                        <div class="heng"></div>
                        <div class="text-[12px] font-semibold">{{
                            $t("累计佣金") }}</div>
                    </div>
                    <div>
                        <span class="text-[10px] font-medium text-[#286EE0]" @click="fetchyongjinrule">{{ $t($t("规则说明"))
                            }}</span>
                    </div>

                </div>
                <div class="gradient-border-box mb-[20px]" v-if="yongjinData.brokerage_pool">
                    <div class="px-[28px] py-[20px]">
                        <div class="flex items-center justify-between mb-[10px]">
                            <span class="text-[13px] font-bold pr-[20px]">{{ $t("累计总额") }}：</span>
                            <div class="font-semibold text-[12px] text-[#FFFB00]">
                                {{ yongjinData?.brokerage_pool }}</div>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-[13px] font-bold pr-[20px]">{{ $t("可领取余额") }}：</span>

                            <div class="font-semibold text-[12px] text-[#FFFB00]">
                                {{ yongjinData?.receivable }}</div>
                        </div>


                        <div class="flex justify-end mt-[10px]">
                            <div :class="[` rounded-[7px] text-[12px] font-semibold  px-[15px] py-[4px] text-white`,yongjinData?.receivable_value ? 'bg-[#FFBB01]' : 'bg-[#808080]']"
                                @click="handlereceive(yongjinData?.receivable_value)">{{
                                    $t("领取") }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between pb-[20px]" v-if="active_detail.length > 0">
                    <div class="flex items-center">
                        <div class="heng"></div>
                        <div class="text-[12px] font-semibold">{{
                            $t("每日奖励") }}</div>
                    </div>
                </div>

                <div class="promotional mb-[15px]" v-if="active_detail.length > 0">
                    <div class="flex  justify-between items-center">
                        <div class="flex flex-1 mr-[10px] flex-col mt-[15px] ml-[16px]">
                            <span class="text-[13px] font-bold">{{ $t("每邀请1人奖R$50") }}</span>
                            <span class="text-[12px]  pt-1" style="color: rgba(255,255,255,0.5);">{{ $t("目前进度") }}
                                ：{{ active_detail[0]?.records_count }}/{{ active_detail[0]?.user_count }}</span>
                        </div>
                        <div class="mr-[10px]"
                            @click="user_getReward(active_detail[0]?.can_receive, active_detail[0]?.id)">
                            <span class="text-[12px] font-semibold px-6 py-[7px] bg-[#00AAFF] rounded-[6px]"
                                :class="{ 'button-background': active_detail[0]?.can_receive == 0 }">{{
                                    active_detail[0]?.can_receive == 0
                                        ? $t("未完成") : $t("领取") }}</span>
                        </div>
                    </div>
                    <div class="mt-[23px] ml-[10px] mr-[10px] pb-[15px]">
                        <van-progress pivot-text="" stroke-width="3" color="#FFD900"
                            :percentage="active_detail[0]?.ratio" />
                    </div>
                </div>
                <!-- 七日连续推广 -->

                <div class="flex items-center justify-between pb-[20px]">
                    <div class="flex items-center">
                        <div class="heng"></div>
                        <div class="text-[12px] font-semibold">{{
                            $t("7日推广连奖") }}</div>
                    </div>
                    <div>
                        <span class="text-[10px] font-medium text-[#286EE0]" @click="fetchrule">{{ $t($t("规则说明"))
                        }}</span>
                    </div>
                </div>
                <div class=" grid grid-cols-3 gap-[10px] mb-[20px]">
                    <div v-for="(item, index) in signData" :key="index">
                        <div
                            :class="[`gradient-border-box-small`, item.status == 1 ? '!bg-[#7E380B] !bg-opacity-45' : item.status == 0 ? '!bg-[#FF6200] !bg-opacity-45' : '']">
                            <div class="flex flex-col py-[6px] justify-center items-center">
                                <div class="text-[12px] font-semibold pb-[5px]">{{ $t("第几天", { day: item?.day }) }}
                                </div>
                                <img :src="$getImageUrl('trophy.png')" class="w-[45px] h-[45px] object-contain" />
                                <span class="font-semibold text-[11px] pt-[5px]">{{ item?.award }}</span>
                            </div>
                        </div>
                        <div class="w-full flex justify-center pt-[10px]">
                            <div @click="getSignReward(item)"
                                :class="[`px-[18px] py-[3px] rounded-[5px] border  text-[11px] font-semibold`, item?.status == 1 ? 'bg-[#7E380B] text-[#D1621B] border-[#D1621B]' : item.status == 0 ? 'bg-[#FFBB01] text-white border-[#FFCE47]' : 'bg-[#DE6311] text-[#FFC831] border-[#FF8736]']">
                                {{ item?.status == 2 ? statusTxtSign[0] : statusTxtSign[item?.status + 1] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <van-popup v-model:show="showRule" class="px-[11px] w-full bg-[#00000000]">
        <div class="bg-white rounded-[10px] border border-[#FF5001] ">
            <div class="flex justify-center relative ">
                <span
                    class=" rounded-b-[18px] text-center py-[7px] px-[46px] text-[17px] font-semibold text-[#FF5001] whitespace-nowrap text-ellipsis overflow-hidden w-full">{{
                        $t('如何解锁你的佣金') }}</span>
            </div>
            <div class="m-[10px] p-[10px] min-h-[50px] border border-[#FF5001] rounded-[10px] notice_rich">
                <div v-html="yongjinrule?.content" class="h-[400px] py-3 overflow-auto regras"></div>
            </div>
            <div class="flex justify-center px-[30px]">
                <van-button type="primary" size="large" @click="showRule = false" class="my-[10px]">
                    <span class="text-white text-[14px] font-semibold">{{ $t("确认") }}</span>
                </van-button>
            </div>
        </div>
    </van-popup>
    <van-popup v-model:show="singRule" class="px-[11px] w-full bg-[#00000000]">
        <div class="bg-white rounded-[10px] border border-[#FF5001] ">
            <div class="flex justify-center relative ">
                <span
                    class=" rounded-b-[18px] text-center py-[7px] px-[46px] text-[17px] font-semibold text-[#FF5001] whitespace-nowrap text-ellipsis overflow-hidden w-full">
                    {{ lianxurule?.title }}</span>
            </div>
            <div class="m-[10px] p-[10px] min-h-[50px] border border-[#FF5001] rounded-[10px] notice_rich">
                <div v-html="lianxurule?.content" class="h-[400px] py-3 overflow-auto regras"></div>
            </div>
            <div class="flex justify-center px-[30px]">
                <van-button type="primary" size="large" @click="singRule = false" class="my-[10px]">
                    <span class="text-white text-[14px] font-semibold">{{ $t("确认") }}</span>
                </van-button>
            </div>
        </div>
    </van-popup>
    <van-overlay :show="showloading">
        <van-loading type="spinner" class="loadingtext" />
    </van-overlay>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { AuthService } from "@/api/home";
import { TeamService } from '@/api/team';
import { showToast } from "vant";
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter()
const showRule = ref(false)
const singRule = ref(false)
const showloading = ref(false)

const active_detail = ref([])
// 每日奖励 日拉新活动
const EventRewards = async () => {
    const response = await TeamService.get_event_rewards({ type: 7 })
    active_detail.value = response.data

}
//领取奖励
const user_getReward = async (num, id) => {
    if (num == 0) return;
    try {
        showloading.value = true
        const response = await TeamService.getReward({ "id": id })
        showToast(t("领取成功"))
        EventRewards();
    } catch (error) {
        console.log(error)
    } finally {
        showloading.value = false
    }


}

//七日推广奖励状态
const statusTxtSign = [t("待领取"), t("领取"), t("已领取")]
const signData = ref([])
const fetchSignData = async () => {
    const res = await TeamService.promotion_reward_list()
    signData.value = res
}
//领取七日推广奖励
const getSignReward = async (item) => {
    if (item.status != 0) return;
    try {
        showloading.value = true
        const response = await TeamService.get_promotion_reward({ day: item.day })
        showToast(t("领取成功"))
        fetchSignData()
    } catch (error) {
        console.log(error)
    } finally {
        showloading.value = false
    }

}
//七日连续推广规则
const lianxurule = ref({})
const fetchrule = async () => {
    if (lianxurule.value.content) {
        singRule.value = true
    } else {
        const res = await AuthService.article_details({ type: "16" })
        lianxurule.value = res
        singRule.value = true
    }

}
//获取佣金池数据
const yongjinData = ref({})
const fetchyongjin = async () => {
    const response = await AuthService.brokerage_pool()
    yongjinData.value = response
}
//佣金池规则
const yongjinrule = ref({})
const fetchyongjinrule = async () => {
    if (yongjinrule.value.content) {
        showRule.value = true
    } else {
        const res = await AuthService.article_details({ type: "15" })
        yongjinrule.value = res
        showRule.value = true
    }

}

//领取佣金
const handlereceive = async (num) => {
    try {
        if (num > 0) {
            showloading.value = true
            const res = await AuthService.brokerage_pool_receive()
            showToast(t("领取成功"))
            fetchyongjin();
        } 
    }
    catch (error) {

    } finally {
        showloading.value = false
    }
}
onMounted(async () => {
    try {
        showloading.value = true
        new Promise.all([await fetchyongjin(), await EventRewards(), await fetchSignData()]).then(() => {
            showloading.value = false
        })
    } catch (error) {

    } finally {
        showloading.value = false
    }

})
</script>

<style scoped>
.button-background {
    background: #00AAFF;
}

:deep(.van-nav-bar) {
    background: transparent !important;
}

.van-progress {
    position: relative;
    height: var(--van-progress-height);
    background: #0B0913 !important;
    border-radius: var(--van-progress-height);
}

.promotional {
    background: linear-gradient(89deg, #006DFF 0%, #00D9FF 100%);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 10px;
}

.initial {
    background: linear-gradient(270deg, #6787FF, #4156F1);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
}

.grow {
    background: linear-gradient(270deg, #47B93B, #138416);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
}

.illustrious {
    background: linear-gradient(270deg, #FFCF3E, #FF9D3E);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
}

.elite {
    background: linear-gradient(270deg, #FF7646, #E25625);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
}

.content {
    background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
    padding: 10px;
    color: #0034B4;

    :deep(span) {
        color: #0034B4 !important;
    }

}

.van-progress {
    position: relative;
    height: var(--van-progress-height);
    background: rgba(0, 0, 0, 0.4) !important;
    border-radius: var(--van-progress-height);
}

.gradient-border-box {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
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
.gradient-border-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    /* 渐变背景作为边框 */
    background: linear-gradient(to right, #FF5001, #FFA201, #FF5001);
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

.gradient-border-box-small {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background: rgba(255, 177, 121, 0.25);
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
.gradient-border-box-small::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    /* 渐变背景作为边框 */
    background: linear-gradient(to right, #FF5001, #FFA201, #FF5001);
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

:deep(.notice_rich) {
    p {
        color: black !important;
    }

    span {
        color: black;
    }

    strong {
        color: black !important;
    }

}
.loadingtext {
    height: 100vh;
    /* 设置视口高度 */
    display: flex;
    justify-content: center;
    /* 水平居中 */
    align-items: center;
    /* 垂直居中 */
    margin: 0;
    /* 移除默认外边距 */

}
</style>