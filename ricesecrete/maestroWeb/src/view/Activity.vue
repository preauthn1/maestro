<template>
    <div>
        <van-nav-bar left-arrow @click-left="router.go(-1)">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('活动中心') }}</span>
            </template>
        </van-nav-bar>
        <div class="px-[22px]" v-if="!showloading">
            <div class="gradient-border-box-fixed-leiji relative z-[1] mb-[10px]" v-for="(item, index) in activitylist"
                :key="item?.buttonText">
                <div class="px-[15px] py-[9px] relative z-[2]">
                    <div class="flex justify-between items-center">
                        <div class="text-[12px] font-bold text-white">{{ $t(item.title) }}</div>
                        <div @click="handle(0)" class="bg-[#8D3900] shrink-0 rounded-[6px] border 
                            border-[#BA4B00] px-[18px] py-[3px] text-[12px] font-semibold">
                            {{ $t(item.buttonText) }}
                        </div>
                    </div>
                    <div>
                        <span class="text-[12px] font-medium">{{ $t('说明') }}：</span>
                        <div class="text-[12px] font-medium mt-[10px] bg-[#502506] px-[5px] py-[8px] rounded-[8px]">
                            {{ $t(item.content) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="gradient-border-box-fixed-leiji mb-[10px]" v-if="activityData.length > 0">
                <div class="px-[15px] py-[9px] relative z-[2]">
                    <div class="flex justify-between items-center">
                        <div class="text-[12px] font-bold text-white">{{ activityData[0]?.title }}</div>
                        <div @click="handle(1)"
                            class="bg-[#8D3900] shrink-0 rounded-[6px] border border-[#BA4B00] px-[18px] py-[3px] text-[12px] font-semibold">
                            {{ $t('立即参与') }}
                        </div>
                    </div>
                    <div>
                        <span class="text-[12px] font-medium">{{ $t('说明') }}：</span>
                        <div class="text-container mt-[10px] bg-[#502506] px-[5px] py-[8px] rounded-[8px]">
                            <p ref="descriptionText"
                                
                                v-html="activityData[0]?.content">
                            </p>
                        </div>
                        <!-- <div class="flex justify-end mt-[10px]" v-show="showToggle">
                            <button @click="toggleText">
                                <div class="flex justify-between items-center gap-2">
                                    <span class="text-[12px] font-medium text-[#286EE0]">{{ isExpanded ? $t('收起') :
                                        $t('展开')
                                        }}</span>
                                    <van-icon :name="isExpanded ? 'arrow-up' : 'arrow-down'" color="#286EE0" />
                                </div>
                            </button>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="gradient-border-box-fixed-leiji mb-[10px] relative z-[1]">
                <div class="px-[15px] py-[9px] relative z-[2]">
                    <div class="flex justify-between items-center">
                        <div class="text-[12px] font-bold text-white">{{ $t('官方限定活动') }}</div>
                        <div class="bg-[#8D3900] shrink-0 rounded-[6px] border border-[#BA4B00] px-[18px] py-[3px] text-[12px] font-semibold"
                            @click="handle(2)">
                            {{ $t('立即参与') }}
                        </div>
                    </div>
                    <div>
                        <span class="text-[12px] font-medium">{{ $t('说明') }}：</span>
                        <div class="text-[12px] font-medium mt-[10px] bg-[#502506] px-[5px] py-[8px] rounded-[8px]">
                            {{ $t('交易所将不定期推出的特别奖励活动，具体以交易所的发布的活动公告为准') }}
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <van-popup v-model:show="showRule" class="px-[30px] w-full bg-[#00000000]" close-on-click-overlay>
        <div class="bg-white rounded-[10px] border border-[#FF5001] ">
            <img :src="$getImageUrl('an.png')" class="w-[60px] h-[60px] mx-auto mt-[20px]" alt="">
            <div class="m-[10px] p-[10px] rounded-[10px]">
                <van-field v-model="number" type="number" maxlength="6" :placeholder="$t('请输入领取编码')"
                    v-if="selectedIndex == 0" />
                <van-field v-model="number" type="number" maxlength="6" :placeholder="$t('请输入活动编码')" v-else />
            </div>
            <div class="flex justify-center flex-col px-[50px]">
                <van-button type="primary" size="large" @click="Btn" class="!h-[38px] mb-[10px]"
                    v-if="selectedIndex == 0">
                    <span class="text-white text-[14px] font-semibold">{{ $t("立即领取") }}</span>
                </van-button>
                <van-button type="primary" size="large" @click="Btn" class="!h-[38px] mb-[10px]" v-else>
                    <span class="text-white text-[14px] font-semibold">{{ $t("立即参与") }}</span>
                </van-button>
                <van-button size="large" @click="cancelBtn"
                    style="background: linear-gradient( 89deg, #FF6E35 0%, #FFAD72 100%);" class="!h-[38px] mb-[20px]">
                    <span class="text-white text-[14px] font-semibold">{{ $t("取消") }}</span>
                </van-button>
            </div>
        </div>
    </van-popup>
    <van-overlay :show="showloading">
        <van-loading type="spinner" class="loadingtext" />
    </van-overlay>
</template>

<script setup>
import { showToast } from 'vant'
import { ref, onMounted, computed, onBeforeUpdate, nextTick } from 'vue';
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { TeamService } from '@/api/team'
const { t } = useI18n()
const router = useRouter()
const showRule = ref(false)
const number = ref('')
const activitylist = [
    { buttonText: '立即领取', title: '推广扶持奖励（官方合伙人专属）', content: '官方合伙人团队专属的推广扶持资金，每月考核不定期发放' },
    { buttonText: '立即领取', title: '团队交易量达标奖励（官方合伙人专属）', content: '官方合伙人团队达到不同交易量后可获得对应的达标奖励，每月考核不定期发放' },
    { buttonText: '立即领取', title: '团队交易量月度分红奖励（合伙人与高级代理专属）', content: '达到可获得分红的合伙人及高级代理，根据每月团队交易量按照实际利润比例获得每月的分红奖励、每月发放' },
]
const selectedIndex = ref(0)
const handle = (num) => {
    if (num != 1) {
        showRule.value = true
        selectedIndex.value = num
    } else {
        //joined 1 已参与 0 未参与
        if (activityData.value[0].joined == 1) {
            router.push(`/activitydetails?id=${activityData.value[0].id}`)
        } else {
            showRule.value = true
            selectedIndex.value = num
        }

    }

}
const cancelBtn = () => {
    showRule.value = false
    number.value = ''
}
const Btn = async () => {
    if (!number.value) {
        return showToast(t('请输入领取编码'))
    }
    if (selectedIndex.value != 1) {
        setTimeout(() => {
            if(selectedIndex.value == 0){
                showToast(t('领取码错误'))
            }else{
                showToast(t('活动编码错误'))
            }
            
        }, 1000)
    } else {
        await TeamService.join_activity({ password: number.value, activity_id: activityData.value[0].id })
        showToast(t('参与成功'))
        router.push(`/activitydetails?id=${activityData.value[0].id}`)
    }

}
// 状态定义
// const descriptionText = ref(null); // 文本容器引用
// const isExpanded = ref(false); // 是否展开
// const showToggle = ref(false); // 是否显示按钮
// const lineHeight = ref(20); // 每行的高度（像素）
// const maxLines = ref(3); // 最大显示行数
// const maxHeight = ref(0); // 折叠状态下最大高度

// // 计算折叠状态下文本的高度
// const computedMaxHeight = computed(() => {
//     return lineHeight.value * maxLines.value;
// });

// 在挂载时检查是否需要折叠
const showloading = ref(true)

onMounted(async () => {
    showloading.value = true
    const response = await TeamService.get_event_rewards({ type: 8 })
    activityData.value = response.data
    showloading.value = false
    // nextTick(() => {
    //     if (descriptionText.value) {
    //         const textHeight = descriptionText.value.scrollHeight; // 文本总高度
    //         maxHeight.value = computedMaxHeight.value; // 设置折叠高度限制
    //         showToggle.value = textHeight > maxHeight.value; // 如果超过三行，则显示按钮
    //     }
    // }); // 等待 DOM 更新

});
//参与活动数据
const activityData = ref([])
// 点击按钮时切换折叠/展开状态
const toggleText = () => {
    isExpanded.value = !isExpanded.value;
};

// 监听文本内容变化，动态更新折叠状态
// onBeforeUpdate(() => {
//     if (descriptionText.value) {
//         const textHeight = descriptionText.value.scrollHeight; // 更新文本总高度
//         showToggle.value = textHeight > maxHeight.value; // 动态判断是否需要按钮
//     }
// });

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

:deep(.van-field__control) {
    color: #000000 !important;
}

:deep(.van-field__control::placeholder) {
    color: #000000 !important;
}

:deep(.van-cell) {
    border-radius: 6px;
    padding: 9px 11px !important;
}

.text-container {
    position: relative;
    //   width: 300px; /* 根据需求调整 */
}

.description-text {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    /* 限制显示的行数 */
    overflow: hidden;
    text-overflow: ellipsis;
    /* 超出部分显示省略号 */
    line-height: 1.5;
    /* 可选，设置行高 */
    transition: max-height 0.3s ease;
    /* 平滑过渡 */
}

.description-text1 {
    font-size: 12px;
    line-height: 1.5;
    /* 可选，设置行高 */
    transition: max-height 0.3s ease;
    /* 平滑过渡 */
}

/* 展开按钮样式 */
.toggle-button {
    display: none;
    /* 默认隐藏 */
    position: relative;
    background-color: #ff9900;
    color: white;
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    font-size: 12px;
}

.toggle-button.show {
    display: block;
    /* 启用时显示 */
}
</style>