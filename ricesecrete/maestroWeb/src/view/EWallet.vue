<template>
    <div class="content min-h-[100vh]">
        <van-nav-bar left-arrow :title="$t('余额')" @click-left="router.go(-1)">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('余额') }}</span>
            </template>
        </van-nav-bar>
        <div class=" pl-[16px] pr-[16px]">
            <div class="flex flex-col text-center gradient-border-box-fixed-leiji">
                <div class="text-[11px] mt-[16px] font-semibold">{{ $t("资产总额") }}</div>
                <div class="text-[18px] pt-[10px] font-bold text-[#15CF00]">{{ $sysCurrency}} {{store?.userInfo?.user_info?.user_money_value < 0 ? 0 : store?.userInfo?.user_info?.user_money_value }}</div>
                        <div class="ml-[12px] mr-[12px] mt-[26px]">
                            <van-button type="primary" size="small" class="border-none !rounded-[10px]" block
                                style="background: linear-gradient( 89deg, #FF4901 0%, #FF7801 100%);"
                                @click="router.push('/rechargechannels')">
                                <span class="text-[13px] font-semibold">{{ $t("充值") }}</span>
                            </van-button>
                        </div>
                        <div class="ml-[12px] mr-[12px] mt-[10px]">
                            <van-button type="primary" size="small" block class="border-none !rounded-[10px]"
                                style="background: linear-gradient( 89deg, #014DFF 0%, #01B7FF 100%) !important;"
                                @click="router.push('/withdrawal')">
                                <span class="font-semibold">{{ $t("提取") }}</span>
                            </van-button>
                        </div>
                        <div class="ml-[12px] mr-[12px] mt-[10px] mb-[20px]">
                            <van-button type="default" size="small" block class="border-none rounded-[10px]"
                                style="background: linear-gradient( 89deg, #0FDB00 0%, #99E100 100%);"
                                @click="handlezhuanzhang()">
                                <span class="font-semibold">{{ $t("转账") }}</span>
                            </van-button>
                        </div>
                </div>
            </div>

            <div class="flex justify-between ml-[16px] mr-[16px] mt-[16px]">
                <div class="flex items-center">
                    <div class="heng"></div>
                    <span class="text-[12px] font-semibold">{{ $t("资产明细") }}</span>
                </div>

                <div @click="show = true" class="w-[120px]  flex justify-end">
                    <span
                        class="text-[12px] font-semibold mr-[5px]  max-w-[100px] text-ellipsis overflow-hidden whitespace-nowrap">{{
                            selecttitle }}</span>
                    <van-icon name="arrow-down" size="16" />
                </div>
            </div>
            <div class="ml-[16px] mr-[16px] mt-[11px]">
                <van-list v-model:loading="loading" :finished="finished" :loading-text="$t('加载中')"
                    :finished-text="$t('没有更多了')" @load="onLoad">
                    <div v-for="(item, index) in list"
                        class="gradient-border-box-fixed-leiji  flex justify-between items-center mb-[11px]"
                        style="background: rgba(80,37,6,0.35);">
                        <div class="ml-[21px] flex flex-col mt-[8px] mb-[8px] flex-1">
                            <span class="text-[13px] text-white font-medium">{{ item.title }}
                                <span class="text-[13px] text-white font-medium">{{ getStatusStr(item) }}</span>
                            </span>
                            <span class="text-[12px] text-white font-medium">{{ item.create_time }}</span>
                        </div>
                        <div class="mr-[21px] flex justify-end ">
                            <span class="text-[17px] font-bold text-white text-right"
                                :class="{ 'wallet-expenses-color': item.money < 0 }">{{ $sysCurrency }} {{ item.money }}
                                </span>

                        </div>
                    </div>
                </van-list>

            </div>
            <van-popup v-model:show="show" class="pop-background" position="bottom" style="max-width: var(--maxWidth);">
                <div class="">
                    <div class="flex justify-between w-full">
                        <div @click="cancelSelectedType"
                            class="pt-[22px] pb-[10px] text-[13px] font-semibold text-black w-[134px] text-center">{{
                                $t("取消")
                            }}
                        </div>
                        <div @click="didSelectedType(typeString)"
                            class="pt-[22px] pb-[10px] text-[13px] text-[#FF5001] font-semibold w-[134px] text-center">
                            {{ $t("确认") }}</div>
                    </div>
                </div>

                <div class="h-[87%] overflow-auto w-full choose_item" style="max-width: var(--maxWidth);">
                    <div class="text-center mt-[10px]">
                        <div v-for="(item, index) in typeData" @click="changeType(item)" class=" pb-[20px]">
                            <span :class="{ 'wallet-type-linear': item.value == typeString }"
                                class="text-[13px] text-black font-semibold">{{ $t(item.title) }}</span>
                        </div>
                    </div>
                </div>
            </van-popup>
            <van-popup v-model:show="zhuangzhang"
                class="bg-white w-[90%] !max-w-[400px] rounded-[10px] border border-[#FF5B01]" position="center"
                style="max-width: var(--maxWidth);">
                <div class="flex">
                    <span class="py-1 px-5 bg-[#FF5C02] rounded-tl-[10px] rounded-br-[15px] text-[12px] font-medium">{{
                        $t("转账")
                    }}</span>
                </div>
                <div class="p-[30px] pt-[20px]">
                    <div class="flex justify-center items-center text-[12px] font-bold text-black pb-[10px]">
                        {{ $t("可用余额") }}：<span class="text-[15px] text-[#15CF00] font-bold">{{$sysCurrency}} {{store?.userInfo?.user_info?.user_money_value < 0 ? 0 : store?.userInfo?.user_info?.user_money_value }}</span>
                    </div>
                    <van-field v-model="transferParams.invite_code" maxlength="10" type="number" class="zhuanzhang" :placeholder="$t('请输入收款方的邀请码')">
                        <template #label>
                            <div class="area-code flex items-center px-1 flex-1">
                                <span class="text-[12px] font-semibold text-black shrink-0">{{ $t('邀请码') }}</span>
                                <div class="w-[1px] h-[20px] bg-[#FF5C02] ml-2 shrink-0"></div>
                            </div>
                        </template>
                    </van-field>
                    <van-field v-model="transferParams.money" type="number" maxlength="10" class="zhuanzhangjin" :placeholder="$t('请输入金额')">
                        <template #label>
                            <div class="area-code flex items-center px-1 flex-1">
                                <span class="text-[12px] font-semibold text-black shrink-0">{{ $t('转账金额') }}</span>
                                <div class="w-[1px] h-[20px] bg-[#FF5C02] ml-2 shrink-0"></div>
                            </div>
                        </template>
                        <template #button>
                            <span class="text-[10px] text-[#0195FF] font-semibold"
                                @click="handleTudo(store?.userInfo?.user_info?.user_money_value)">{{ $t("全部") }}</span>
                        </template>
                    </van-field>
                    <van-field v-model="transferParams.password" maxlength="6" class="zhuanzhangpasswoed"
                        :type="is_passwordVisible ? 'text' : 'password'"
                        @click-right-icon="is_passwordVisible = !is_passwordVisible" :placeholder="$t('请输入安全密码')">
                        <template #label>
                            <div class="area-code flex items-center px-1 flex-1">
                                <span class="text-[12px] font-semibold text-black shrink-0">{{ $t('安全密码') }}</span>
                                <div class="w-[1px] h-[20px] bg-[#FF5C02] ml-2 shrink-0"></div>
                            </div>
                        </template>
                        <template #right-icon>
                            <img :src="is_passwordVisible ? $getImageUrl('zhuanzhang-yes.png') : $getImageUrl('zhuanzhang-no.png')"
                                class="w-[15px] h-[15px]" alt="">
                        </template>
                    </van-field>
                    <div class="flex justify-between items-center mt-[20px] mx-[10px] gap-[20px]">
                        <van-button class="border-none !rounded-[10px]" block
                            style="background: linear-gradient( 89deg, #014DFF 0%, #01B7FF 100%);"
                            @click="transferToOthers">
                            <span class="text-[13px] font-semibold">{{ $t("确认") }}</span>
                        </van-button>
                        <van-button type="primary" class="border-none !rounded-[10px]" block
                            
                            @click="canceBtn">
                            <span class="text-[13px] font-semibold">{{ $t("取消") }}</span>
                        </van-button>
                    </div>
                </div>

            </van-popup>
        </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useUserStore } from "@/store/userinfo";
import { AuthService } from "@/api/home";
import { useI18n } from 'vue-i18n';
import { showToast } from "vant";
const { t } = useI18n()
const store = useUserStore()
const router = useRouter()
const show = ref(false);
const zhuangzhang = ref(false);
const list = ref([]);
const loading = ref(false);
const finished = ref(false);
const selecttitle = ref('');
//安全密码是否可见
const is_passwordVisible = ref(false);
const typeData = ref([
    { value: 0, title: "全部" },
    { value: 1, title: "用户充值" },
    { value: 2, title: "用户提现" },
    { value: 3, title: "任务返利" },
    { value: 4, title: "储值返利" },
    { value: 5, title: "抽奖活动" },
    { value: 6, title: "签到活动" },
    { value: 7, title: "升级返利" },
    { value: 8, title: "分享奖励" },
    { value: 9, title: "一级返利" },
    { value: 10, title: "二级返利" },
    { value: 11, title: "三级返利" },
    { value: 12, title: "广告位" },
    { value: 13, title: "AI智能交易" },
    { value: 14, title: "AI智能托管收益" },
    { value: 15, title: "直推奖励" },
    { value: 16, title: "广告位收益" },
    { value: 19, title: "团队活跃奖" },
]);
//转账接口参数
const transferParams = reactive({
    invite_code: "",
    money: "",
    password: ""
})
const getStatusStr = (item) => {
    if (item.detail_type == 2) {
        if (item.withdrawals_status == 0 || item.withdrawals_status == 1 || item.withdrawals_status == 2 || item.withdrawals_status == 7) {
            return `(${t('审核中')})`;
        } else if (item.withdrawals_status == 4) {
            return `(${t('成功')})`; 
        } else {
            return `(${t('失败')})`;
        }
        return
    }
    return '';
}

const pageInfo = reactive({
    page: 1,
    limit: 10,
})

const typeString = ref(0);
const typeSelected = ref(0);
function changeType(str) {
    typeString.value = str.value;
}
const didSelectedType = async (type) => {
    typeSelected.value = type;  
    gettitle()
    show.value = false;
    pageInfo.page = 1;
    finished.value = false
    list.value = [];
    onLoad();
}
const onLoad = async () => {

    loading.value = true
    const res = await AuthService.money_log({ type: 1, "detail_type": typeString.value, ...pageInfo })
    loading.value = false
    list.value = [...list.value, ...res.data]
    if (res.data.length >= 10) {
        pageInfo.page++
        return;
    }
    finished.value = true
}
const gettitle = () => {
    typeData.value.forEach((item) => {
        if (item.value == typeString.value) {
            selecttitle.value = t(item.title)
        }
    })
}

//要是这个
const handlezhuanzhang = async () => {
    await store.getUserInfo()
    if(store?.userInfo?.user_info?.transfer_money_status == 0){
        return showToast(t("仅限官方合伙人有权限"))
    }
    zhuangzhang.value = true;
}
//转账给他人
const transferToOthers = async() => {
    if(!transferParams.invite_code) {
        return showToast(t("请输入收款方的邀请码"))
    }
    if(!transferParams.money) {

        return showToast(t("请输入转账金额"))
    }
    if(transferParams.money <= 0) {
        return showToast(t("请输入有效的转账金额"))
    }
    if(!transferParams.password) {
        return showToast(t("请输入安全密码"))
    }   
    const res = await AuthService.transfer_money(transferParams)
    showToast(t("转账成功"))
    store.getUserInfo()
    show.value = false;
    pageInfo.page = 1;
    finished.value = false
    list.value = [];
    onLoad();
    zhuangzhang.value = false;
    transferParams.invite_code = "";
    transferParams.money = "";
    transferParams.password = "";
    
}
const canceBtn = () => {
    zhuangzhang.value = false;
    transferParams.invite_code = "";
    transferParams.money = "";
    transferParams.password = "";
}

//点击全部
const handleTudo = (value) => {
    if(value < 0){
        transferParams.money = 0;
    } 
    else{
        transferParams.money = value;
    } 
}

function cancelSelectedType() {
    typeString.value = typeSelected.value;
    show.value = false;
}
onMounted(() => {
    gettitle()
    onLoad()
})
</script>

<style scoped>
.pop-background {
    z-index: 3000;
    width: 100%;
    height: 60%;
    border-radius: 15px 15px 0 0;
    background: #FFFFFF;
    border: 1px solid #FF5B01;
    border-bottom: none;
    backdrop-filter: blur(5px);
}



.wallet-top-borderview {
    background: rgba(21, 19, 30, 0.74);
    /* 渐变边框 */
    border-radius: 5px;
    /* padding: 2px; */
}

:deep(.van-popup--bottom) {
    left: auto;
}

.pop-background::-webkit-scrollbar {
    display: none;
    /* Webkit browsers */
}

.choose_item::-webkit-scrollbar {
    display: none;
    /* Webkit browsers */
}

.wallet-expenses-color {
    color: #FD0000;
}

.wallet-type-backview {
    /* position: relative; */
    border-radius: 26px 26px 0 0;
    background: #ffffff33;
    border: 1px solid #FFFFFF;
}

.wallet-type-linear {
    font-weight: 600;
    font-size: 13px;
    color: #FF5001;
    /* background: linear-gradient(180deg, #FDC2D8 0%, #FCA65E 35.009765625%, #FF7983 64.990234375%, #041282 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent; */
}

.content {
    background: url("../assets/images/home/home_bg.png") no-repeat top center;
    background-size: var(--maxWidth) auto;
    min-height: 100vh;
    background-attachment: fixed;
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

:deep(.zhuanzhang) {
    .van-field__control {
        padding-left: 70px;
        color: #000000;
    }
}

:deep(.zhuanzhangjin) {
    .van-field__control {
        padding-left: 90px;
        color: #000000;
    }
}

:deep(.zhuanzhangpasswoed) {
    .van-field__control {
        padding-left: 40px;
        color: #000000;
    }
}
</style>