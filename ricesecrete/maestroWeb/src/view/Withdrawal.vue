<template>
    <div class="content pb-[30px]">
        <van-nav-bar left-arrow :title="$t('提取')" @click-left="router.go(-1)" @click-right="router.push('/eWallet')">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('提取') }}</span>
            </template>
            <template #right>
                <van-icon name="todo-list-o" color="#FFFFFF" />
            </template>
        </van-nav-bar>
        <div class="gradient-border-box-fixed-leiji mx-[11px]">
            <div class="">
                <div class="ml-[30px] mr-[30px] pt-[17px] pb-[17px] flex justify-between items-center">
                    <span class="text-[12px] font-medium">{{ $t("账户余额") }}</span>
                    <span class="text-[15px] font-semibold text-[#15CF00]">{{ $sysCurrency }}
                        {{ store?.userInfo?.user_info?.user_money_value < 0 ? 0 :
                            store?.userInfo?.user_info?.user_money_value }} </span>
                </div>
            </div>
        </div>
        <div class="flex items-center mx-[11px] my-[20px] mb-[10px]">
            <div class="heng"></div>
            <div class="text-[12px] font-bold">{{ $t("银行卡") }}</div>

        </div>
        <div class="rounded-[5px]">
            <div class="pt-[8px] mx-[11px]">
                <div class="back-crad-content flex flex-col">

                    <span class="text-[17px] font-semibold pt-[18px] ml-[10px]">{{ store.userInfo?.user_info?.bank_name
                    }}</span>
                    <!-- <div class="flex items-center gap-3">

                        <span class="text-[20px] font-medium ml-[10px] mt-[20px] " v-show="!showName">{{
                            store.userInfo?.user_info?.bank_number ?
                                (store.userInfo?.user_info?.bank_number).slice(0, 4) : "****" }} **** ****
                            {{ store.userInfo?.user_info?.bank_number ?
                                (store.userInfo?.user_info?.bank_number).slice(-4) :
                                "****" }}</span>
                        <span
                            class="text-[20px] font-medium ml-[10px] mt-[20px] w-[80%] overflow-hidden text-ellipsis whitespace-nowrap"
                            v-show="showName">{{
                                (store.userInfo?.user_info?.bank_number) }}</span>
                        <van-icon @click="showName = !showName" class="mt-[20px]"
                            :name="showName ? 'eye-o' : 'closed-eye'" size="16" />
                    </div> -->
                    <span class="text-[14px] font-semibold  pt-[8px] ml-[10px]">{{ store?.userInfo?.user_info?.document_type
                    }}：{{
                            store?.userInfo?.user_info?.document_id }}</span>
                    <span
                        class="text-[14px] font-semibold ml-[10px] mt-[8px] mb-[16px] w-[80%] overflow-hidden text-ellipsis whitespace-nowrap">{{
                            $t("持卡人") }}：{{
                            store.userInfo?.user_info?.true_name }}</span>

                </div>
            </div>
        </div>
        <div class="mx-[11px] mt-[15px] mb-[30px]">
            <!--  <div>
                <span class="text-[15px]">{{$t("提现")}}</span>
                <van-field v-model="params.withdraw_address" type="texts" :placeholder="$t('请输入或长按粘贴提币地址')" class="mt-[10px]" />
            </div> -->
            <!-- <div>
                <span class="text-[13px] font-semibold">{{ $t("提款渠道") }}</span>
                <div class="flex py-3">
                    <div class="p-4 ml-[10px] rounded-[10px]  border" :class="active == index ? 'bg-[#007AFF]' : ''" v-for="item,index in tabList?.bank_platforms" @click="active = index">{{ item?.name }}</div>
                </div>
            </div> -->

            <div>
                <div class="flex items-center">
                    <div class="heng"></div>
                    <span class="text-[12px] font-bold">{{ $t("提款金额") }}</span>
                </div>
                <div class="gradient-border-box-fixed-leiji my-[20px]">
                    <van-field v-model="params.money" type="number" @input="onInput" :maxlength="50"
                        :placeholder="$t('请输入提现金额')" class="border-none mb-0">
                        <template #button>
                            <van-button size="mini" type="default" color="#15131D00"
                                @click="handleTudo(store?.userInfo?.user_info?.user_money_value)">
                                <span class="text-[#15CF00] text-[12px] font-bold">{{ $t("全部") }}</span>
                            </van-button>
                        </template>
                    </van-field>
                </div>
            </div>

            <!-- <div class="flex items-center mt-[5px] mb-[12px]">
                <van-icon name="warning-o" size="19" />
                <div class="flex flex-col">
                    <span class="text-[10px] text-left ml-[10px]">
                        {{ $t("最低提款金额") }}：{{ tabList?.platforms[active]?.min_withdraw_money }} {{ $sysCurrency }}
                    </span>
                    <span class="text-[10px] text-left ml-[10px]">
                        {{ $t("最高提款金额") }}：{{ tabList?.platforms[active]?.max_withdraw_money }} {{ $sysCurrency }}
                    </span>
                </div>
            </div> -->
            <div>
                <div class="flex items-center">
                    <div class="heng"></div>
                    <span class="text-[12px] font-bold">{{ $t("安全密码") }}</span>
                </div>
                <div class="gradient-border-box-fixed-leiji my-[20px]">
                    <van-field v-model="params.password" type="password" :maxlength="20" :placeholder="$t('请输入安全密码')"
                        class="border-none mb-0" />
                </div>

            </div>
            <div class="mt-[10px] flex justify-between">
                <div>
                    <span class="text-[12px] font-medium">{{ $t("手续费率") }}：</span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[12px] font-medium">{{ (Number(tabList?.withdraw_rate) * 100) }}%</span>
                </div>
            </div>
            <div class="mt-[10px] flex justify-between">

                <div>
                    <span class="text-[12px] font-medium">{{ $t("手续费") }}：</span>
                </div>
                <div class="flex flex-col text-right">
                    <span class="text-[12px] font-medium">{{ premium }} {{ $sysCurrency }}</span>
                    <span class="text-[#15CF00] text-[12px]">{{ $t('免费次数')
                    }}{{ tabList?.free_withdraw_fee_num }}</span>
                </div>
            </div>
            <van-button block size="large" type="primary" class="mt-[30px] rounded-[10px]" @click="Confirm">
                <span class="text-[13px] font-semibold">{{ $t("确定") }}</span>
            </van-button>

            <!-- <div class="flex flex-col">
                <span class="text-[10px] mt-[10px]">1、为了维持平台稳定运行，每次提现收取1U固定费用作为维护费用</span>
                <span class="text-[10px] mt-[10px]">2、每次提款将根据提款金额收取不同的税费。费用如下10-100U收8%稅 100-500U收5%稅</span>
                <span class="text-[10px] mt-[10px]">3、提现将经过多个节点确认审核，到账时间为系统时间T+5天</span>
            </div> -->
        </div>

        <div class="gradient-border-box-fixed-leiji py-[19px] px-[20px] mx-[10px]"
            style="background: rgba(80,37,6,0.35);">
            <div class="">
                <span class="font-semibold text-white">{{ $t("温馨提醒") }}</span>
            </div>
            <div class="pt-[1px]">
                <div v-html="rule?.content"></div>
            </div>
        </div>
        <van-popup v-model:show="showCenter" :close-on-click-overlay="false" class="rounded-[10px]">
            <div class="bg-white relative border border-[#FF5001] rounded-[10px]" style="max-width: 324px;">
                <div class="flex flex-col items-center ">
                    <div class="w-full flex">
                        <span class="text-[15px] bg-[#FF5B01] popup-title px-[20px] py-1 font-semibold">{{ $t('温馨提示')
                            }}</span>
                    </div>

                    <span class="mt-[16px] mx-[16px] px-[20px] text-[13px] text-black font-semibold text-center">{{
                        $t('您当前还没有绑定银行卡，请前往绑定!')
                    }}</span>
                    <div>
                        <van-button round block type="primary" class="mt-[32px] mb-[24px]" @click="jumpbank">
                            <span class=" font-bold">{{ $t("确认") }}</span>
                        </van-button>
                    </div>

                </div>
            </div>
        </van-popup>
        <van-popup v-model:show="showToase" class="rounded-[10px]">
            <div class="bg-white relative border border-[#FF5001] rounded-[10px]" style="max-width: 324px;">
                <div class="flex flex-col items-center ">
                    <div class="w-full flex">
                        <span class="text-[15px] bg-[#FF5B01] popup-title px-[20px] py-1 font-semibold">{{ $t('温馨提示')
                            }}</span>
                    </div>

                    <span class="mt-[16px] mx-[16px] px-[20px] text-[13px] text-black font-semibold text-center">{{
                        messagecontent
                        }}</span>
                    <div>
                        <van-button round block type="primary" class="mt-[32px] mb-[24px]" @click="showToase = false">
                            <span class=" font-bold">{{ $t("确认") }}</span>
                        </van-button>
                    </div>

                </div>
            </div>
        </van-popup>
    </div>
</template>

<script setup>
import Tabs from "@/components/Tabs.vue"
import { ref, reactive, onMounted, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import { AuthService } from "@/api/home";
import { useUserStore } from "@/store/userinfo";
import { showToast } from "vant";
import { useI18n } from 'vue-i18n';
const route = useRoute();
const { t } = useI18n();
const store = useUserStore();
const router = useRouter()
const tabList = ref()
const active = ref(0)
const rule = ref()
const showName = ref(false)
const showCenter = ref(false);
const showToase = ref(false)
const messagecontent = ref("")
/* const showkyc_status = ref(false) */
const params = reactive({
    money: "",
    password: "",
})
const getWidType = async () => {
    const res = await AuthService.withdrawals({})
    tabList.value = res
    tabList.value.bank_platforms.forEach((item, index) => {
        if (item.id == route.query.id) {
            active.value = index
        }
    });
}

const premium = computed(() => {
    return Number((params.money * tabList.value?.withdraw_rate)).toFixed(2)
})
const Confirm = async () => {
    if (!params.money) {
        return showToast(t("请输入提现金额"))
    } else {
        if (Number(params.money) < Number(tabList.value?.bank_platforms[active.value]?.min_withdraw_money)) {
            return showToast(`${t("最低提现金额")}${tabList.value?.bank_platforms[active.value]?.min_withdraw_money}`)
        }
        if (Number(params.money) > Number(tabList.value?.bank_platforms[active.value]?.max_withdraw_money)) {

            return showToast(`${t("最高提现金额")}${tabList.value?.bank_platforms[active.value]?.max_withdraw_money}`)
        }
    }
    if (!params.password) {
        return showToast(t("请输入安全密码"))
    }

    const res = await AuthService.applicationwithdrawals({ ...params, "platform_id": tabList.value?.bank_platforms[active.value]?.id })
    if (res.code == -1) {
        messagecontent.value = res.msg;
        showToase.value = true;
        return
    }

    await store.getUserInfo()
    params.money = ""
    params.password = ""
    showToast(t("申请成功"))
    //getWidType();
    router.replace("/eWallet")
}
const jumpbank = () => {
    // router.push("/bankcard")
    router.push({ path: '/bankcard', query: { cate: 1 } })
}

//处理全部
const handleTudo = (value) => {
    if (value > 0) {
        params.money = value
    } else {
        params.money = 0;
    }
}
const onInput = (event) => {
    // 获取当前输入的值
    let value = event.target.value;
    // 使用正则表达式只保留数字和小数点，并限制小数点后两位
    value = value.replace(/[^0-9.]/g, ''); // 过滤掉非数字和非小数点的字符

    // 若出现多个小数点，保留第一个
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts.slice(1).join('').substring(0, 2);
    } else if (parts.length === 2) {
        // 限制小数点后最多两位
        value = parts[0] + '.' + parts[1].substring(0, 2);
    }
    // 更新金额值
    params.money = value;
}

onMounted(async () => {
    await store.getUserInfo()
    getWidType()

    if (!store.userInfo?.user_info?.bank_name) {
        showCenter.value = true
    }

    const res = await AuthService.article_details({ type: "12" });
    rule.value = res


})
</script>

<style scoped>
.wallet-top-borderview {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 13px;
    border: 1px solid #FFFFFF;
}

::v-deep .van-tabs__line {
    height: 3px;
    background: #fff;
}

.title_active {
    color: #0937AD !important;
}

.back-crad-content {
    width: 100%;
    height: auto;
    background: url("@/assets/images/icon_back_card.png") no-repeat center;
    background-size: cover;
    border-radius: 10px;
}

.content {
    background: url("../assets/images/home/home_bg.png") no-repeat top center;
    background-size: 42.5rem auto;
    min-height: 100vh;
    background-attachment: fixed;
}

.popup-title {
    border-radius: 10px 0px 15px 0px;
}

.note-background {
    background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
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

:deep(.van-field__control::placeholder) {
    font-size: 11px;
    color: #FFFFFF !important;
}
</style>