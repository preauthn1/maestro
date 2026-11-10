<template>
    <div class="content pt-[5.6rem]">
        <van-nav-bar fixed left-arrow :title="$t('绑定银行卡')" @click-left="router.go(-1)">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('绑定银行卡') }}</span>
            </template>
        </van-nav-bar>
        <div class="ml-[16px] mr-[16px]">
            <div class="flex">
                <div class="heng"></div>
                <span class="text-[12px] font-semibold">{{ $t("选择银行卡类型") }}</span>
            </div>

            <!-- <div class="flex m-4">
                <div class="p-4 text-[14px] font-semibold border rounded-[8px]"
                    :class="pageInfo.type == 1 ? 'bg-[#0024ED]' : ''" @click="pageInfo.type = 1">{{ $t("银行卡") }}</div>
                <div class="p-4 text-[14px] font-semibold ml-4 border rounded-[8px]"
                    :class="pageInfo.type != 1 ? 'bg-[#0024ED]' : ''" @click="pageInfo.type = 2">{{ $t("PIX") }}</div>
            </div> -->
            <div class="mt-[15px]">
                <div>

                    <span class="text-[12px] font-semibold">{{ $t("银行卡名称") }}</span>
                    <div @click="show = true"
                        class="gradient-border-box-fixed-leiji mt-[10px] flex justify-between h-[50px] items-center">
                        <span class="text-[#fff] text-[11px] ml-[12px] font-semibold"
                            :class="{ 'selector-text-white': typeData.filter(item => item?.id == typeSelected)[0]?.name }">{{
                                typeData.filter(item => item?.id == typeSelected)[0]?.name ? typeData.filter(item =>
                                    item?.id
                                    == typeSelected)[0]?.name : $t('请选择')}}</span>
                        <van-icon name="arrow" size="13" class="mr-[12px]" />
                    </div>
                </div>

                <div class="mt-[20px] text-left">
                    <span class="text-[12px] font-semibold">{{ $t("账户类型") }}</span>
                    <div @click="accountTypeShow = true"
                        class="gradient-border-box-fixed-leiji mt-[10px] flex justify-between h-[50px] items-center">
                        <span class="text-[#fff] text-[11px] ml-[12px] font-semibold"
                            :class="{ 'selector-text-white': accountTypeData.filter(item => item?.id == accountTypeSelected)[0]?.value }">{{
                                accountTypeData.filter(item => item?.id == accountTypeSelected)[0]?.value ?
                                    accountTypeData.filter(item =>
                                        item?.id
                                        == accountTypeSelected)[0]?.value : $t('请选择')}}</span>
                        <van-icon name="arrow" size="13" class="mr-[12px]" />
                    </div>
                </div>
                <div class="mt-[20px] text-left">
                    <span class="text-[12px] font-semibold">{{ $t("账户账号") }}</span>
                    <div class="gradient-border-box-fixed-leiji my-[10px] py-[5px]">
                        <van-field class="border-none  !mb-0" v-model="pageInfo.document_id" label=""
                            :placeholder="$t('请输入账户账号')" maxlength="50"></van-field>
                    </div>

                </div>
                <span class="text-[12px] font-semibold">{{ $t("请输入您的PIX账户号码,并包含国家区号(+55),共计14位数.") }}</span>
                <div class="mt-[20px] text-left">
                    <span class="text-[12px] font-semibold">{{ $t("姓名") }}</span>
                    <div class="gradient-border-box-fixed-leiji my-[10px] py-[5px]">
                        <van-field class="border-none  !mb-0" v-model="pageInfo.true_name" label=""
                            :placeholder="$t('请输入您的真实姓名')" maxlength="50"></van-field>
                    </div>
                </div>
                <!-- <div class="mt-[20px] text-left">
                    <span class="text-[12px] font-semibold">{{ $t("PIX账号") }}</span>
                    <div class="gradient-border-box-fixed-leiji my-[10px] py-[5px]">
                        <van-field class="border-none  !mb-0" v-model="pageInfo.bank_number" maxlength="50" label=""
                            :placeholder="$t('请输入您的PIX账号')" />
                    </div>
                </div>
                <span class="text-[12px] font-semibold">{{ $t("请输入您的PIX号码,共计11位数") }}</span> -->
            </div>

            <div class="mt-[40px] pb-[42px]">
                <van-button round block type="default" size="large"
                    style="background: linear-gradient( 89deg, #FF4901 0%, #FF7801 100%);" @click="bankcardBtn">
                    <span class=" font-bold">{{ $t("确定") }}</span>
                </van-button>
            </div>
        </div>
        <van-popup v-model:show="show" class="pop-background" position="bottom">
            <div class=" flex justify-between w-full" style="max-width: var(--maxWidth);">
                <div @click="cancelSelectedType"
                    class="mt-[16px] text-[13px] font-semibold text-[#000000] w-[134px] text-center">
                    {{ $t("取消") }}</div>
                <div class="flex flex-col items-center mt-[16px]">
                    <div class="mb-[10px] pt-[16px] text-[15px]  font-bold text-black">{{ $t("选择银行卡") }}</div>
                </div>

                <div @click="didSelectedType(typeString)"
                    class="pt-[16px] font-semibold text-[#FF5001] w-[134px] text-center">
                    {{ $t("确认") }}</div>

            </div>
            <!-- <div class="mx-[40px] keyword">
                <van-field v-model="keywords" left-icon="search" class="text-black" :placeholder="$t('请输入关键字')"
                    @update:model-value="stabilization" />
            </div> -->
            <div class=" w-full h-[72%] overflow-auto choose_item" style="max-width: var(--maxWidth);">
                <div class="text-center mt-[20px] ">
                    <div v-for="(item, index) in searchkeywords" @click="changeType(item.id)" class="pb-[20px]"
                        v-if="searchkeywords.length > 0">
                        <span
                            :class="['text-[13px]  font-semibold', item.id == typeString ? 'text-[#FF5001]' : 'text-black']">{{
                                item.name }}</span>
                    </div>
                    <div class="text-[12px] text-black" v-else>{{ $t('暂无结果') }}</div>
                </div>
            </div>
        </van-popup>

        <van-popup v-model:show="accountTypeShow" class="pop-background" position="bottom">
            <div class="flex justify-between w-full" style="max-width: var(--maxWidth);">
                <div @click="cancelSelectedAccountType"
                    class="mt-[16px] text-[13px] text-black font-semibold w-[134px] text-center">
                    {{ $t("取消") }}</div>
                <div class="flex flex-col items-center mt-[16px]">
                    <div class="mb-[10px] pt-[16px] text-[15px]  font-bold text-black">{{ $t("请选择账户类型") }}</div>
                </div>

                <div @click="didSelectedAccountType(accountTypeString)"
                    class="pt-[16px] text-[13px] text-[#FF5001] w-[134px] text-center font-semibold">
                    {{ $t("确认") }}</div>
            </div>

            <div class="w-full h-[72%] overflow-auto choose_item" style="max-width: var(--maxWidth);">
                <div class="text-center mt-[20px]">
                    <div v-for="(item, index) in accountTypeData" @click="changeAccountType(item.id)" class="pb-[20px]">
                        <span
                            :class="['text-[13px]  font-semibold', item.id == accountTypeString ? 'text-[#FF5001]' : 'text-black']">{{
                                item.value }}</span>
                    </div>
                </div>
            </div>
        </van-popup>
        <van-popup v-model:show="showCenter" class="rounded-[10px]">
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
                        <van-button round block type="primary" class="mt-[32px] mb-[24px]" @click="showCenter = false">
                            <span class=" font-bold">{{ $t("确认") }}</span>
                        </van-button>
                    </div>

                </div>
            </div>
        </van-popup>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { AuthService } from "@/api/home";
import { showToast } from "vant";
import { useUserStore } from '../store/userinfo';
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const showCenter = ref(false)
const messagecontent = ref("")
const router = useRouter()
const show = ref(false);
const store = useUserStore();
const typeString = ref(0);
const typeSelected = ref();
const keywords = ref('');
const pageInfo = reactive({
    true_name: "",
    // bank_number: "",
    bank_id: "",
})
function changeType(id) {
    typeString.value = id;
}
const searchkeywords = ref([])
const didSelectedType = async (id) => {
    typeSelected.value = id;
    show.value = false;
}
function cancelSelectedType() {
    typeString.value = typeSelected.value;
    show.value = false;
}
const typeData = ref([]);
const accountTypeShow = ref(false);
const accountTypeString = ref(0);
const accountTypeSelected = ref();
const accountTypeData = ref([
    { id: 1, value: 'CPF' },
    { id: 2, value: 'CNPJ' },
    { id: 3, value: 'PHONE' },
    { id: 4, value: 'EMAIL' },
    // { id: 5, value: 'EVP' }
]);

function changeAccountType(id) {
    accountTypeString.value = id;
}

const didSelectedAccountType = async (id) => {
    accountTypeSelected.value = id;
    accountTypeShow.value = false;
}

function cancelSelectedAccountType() {
    accountTypeString.value = accountTypeSelected.value;
    accountTypeShow.value = false;
}

const bankcardBtn = async () => {
    if (!typeSelected.value) {
        return showToast(t("请选择银行卡名称"))
    }
    if (!accountTypeSelected.value) {
        return showToast(t("请选择账户类型"))
    }
    if (!pageInfo.true_name) {
        return showToast(t("请输入您的真实姓名"))
    }
    // if (!pageInfo.bank_number) {
    //     return showToast(t("请输入银行卡卡号"))
    // }

    pageInfo.bank_id = typeData.value.filter(item => item.id == typeSelected.value)[0]?.id
    pageInfo.document_type = accountTypeData.value.filter(item => item.id == accountTypeSelected.value)[0]?.value

    const res = await AuthService.bind_bank({ ...pageInfo })
    if (res.code == -1) {
        messagecontent.value = res.msg;
        showCenter.value = true;
        return
    }
    showToast(t("绑定成功"))
    store.getUserInfo();
    router.go(-1)

}
const handlekeyword = (e) => {
    typeString.value = 0;
    typeSelected.value = null;
    if (!e) {
        return searchkeywords.value = typeData.value
    }
    searchkeywords.value = typeData.value.filter(item => item.name.includes(e))
}
//防抖功能

const debounce = (func, wait) => {
    let timeout;

    return function (...args) {
        const context = this;

        clearTimeout(timeout);

        timeout = setTimeout(() => {
            func.apply(context, args);
        }, wait);
    };
}

const stabilization = debounce(handlekeyword, 300)
onMounted(async () => {
    if (store.userInfo?.user_info?.bank_name) {
        router.back();
    }
    const res = await AuthService.bank_list();
    typeData.value = res
    searchkeywords.value = typeData.value;
})
</script>

<style scoped>
.selector-text-gray {
    color: #89898A;
}

.selector-text-white {
    color: #FFFFFF;
}

.popup_bg {
    background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
}

.popup-title {
    border-radius: 10px 0px 15px 0px;
}

.wallet-type-backview {
    background-color: #15131E;
    border-radius: 26px 26px 0 0;
}

.choose_item::-webkit-scrollbar {
    display: none;
    /* Webkit browsers */
}

.content {
    background: url("../assets/images/home/home_bg.png") no-repeat top center;
    background-size: var(--maxWidth) auto;
    min-height: 100vh;
    background-attachment: fixed;
}

:deep(.van-popup--bottom) {
    max-width: var(--maxWidth) !important;
    left: auto !important;
}


.van-button--default {
    border: none !important;
    border-radius: 13px !important;
    background: linear-gradient(180deg, rgba(0, 122, 255, 1), #006BE8);
    box-shadow: 0px 1px 2px 0px rgba(15, 9, 13, 0.1);
    border-radius: 18px;
}

:deep(.van-field__control::placeholder) {
    font-size: 11px;
    color: #FFFFFF !important;
}

:deep(.keyword>.van-cell) {
    padding: 4px !important;
}

:deep(.keyword) {
    .van-field__control {
        font-size: 11px;
        color: black !important;
    }

    .van-field__control::placeholder {
        color: black !important;
    }
}

.pop-background {
    width: 100%;
    height: 60%;
    border-radius: 15px 15px 0 0;
    background: #FFFFFF;
    border: 1px solid #FF5B01;
    border-bottom: none;
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
