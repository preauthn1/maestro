<template>
    <div class="content pb-[20px]">
        <div class="">
            <van-nav-bar  left-arrow :title="$t('签到')" @click-left="router.go(-1)"></van-nav-bar>
            <div class="ml-[18px] mr-[18px]">
                <img :src="$getImageUrl('qiandao_bg.png')" alt="">
            </div>
            <div class="grid grid-cols-4 gap-3 mt-[10px] pb-[10px] ml-[18px] mr-[18px]">
                <div class="flex flex-col" v-for="(item, index) in signData" :key="index">
                    <div class="sign-back-borderview"
                        :class="{ 'sign-back-signed': (item?.status == 1 || item?.status == 2), 'sign-back-nosign': item.status == 0 }">
                        <div class="flex flex-col justify-around items-center rounded-[10px]" style="background: rgba(255,255,255,0.2);border: 1px solid #fff;"
                            :class="{ 'sign-background-linear': item?.status == 1 }">
                            <span class="text-[#89898A] text-[13px] mt-[5px]"
                                :class="{ 'sign-text-ablesign': item?.status != 2 }">{{ $t("第几天", { day: item?.day }) }}</span>
                            <img :src="$getImageUrl('icon_sign_coin.png')" class="w-[26px] h-[23px] mt-[5px] mb-[8px]">
                            <span class="text-[#6EEB54] text-[14px] font-semibold mb-[10px]">{{ item?.award }}
                                {{ $sysCurrency }}</span>
                        </div>
                    </div>
                    <div @click="toSignAction(item)"
                        class="bg-[#3158FF] rounded-[5px] ml-[8px] mr-[8px] mt-[10px] text-center flex items-center justify-center"
                        :class="{ 'sign-button-signed': item?.status == 2, 'sign-button-ablesign': item?.status == 1 }">
                        <span class="text-[#fff] text-[12px] pt-[3px] pb-[3px]"
                            :class="{ 'sign-text-ablesign': item.status == 1 }">{{ $t(signstatus[item?.status]) }}</span>
                    </div>
                </div>
            </div>
            <div class="mx-[15px] mt-[30px] notice_rich">
                <div class="p-[15px]" v-html="rule?.content"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import { useRouter } from "vue-router";
import { AuthService } from "@/api/home";
import { showToast } from "vant";
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter()
const rule = ref()
//signType 0:待签到 1:可签到 2:已签到

const signData = ref([]);

const signstatus = ref(["待签到", "签到", "已签到"])
const toSignAction = async (item) => {
    if (item.status != 1) return;
    const res = await AuthService.sign({ "sign_id": item.sign_id })
    showToast(t("签到成功"))
    const resList = await AuthService.signList()
    signData.value = resList
}
onMounted(async () => {
    const res = await AuthService.signList()
    signData.value = res
    const res01 = await AuthService.article_details({ type: "9" })
    rule.value = res01
})
</script>

<style scoped>
.content {
    background: url('../assets/images/home/home_bg.png') no-repeat top center;
    background-size: var(--maxWidth) auto;
    min-height: 100vh;
    background-attachment: fixed;
}

.sign-back-borderview {
    border-radius: 6px;
    padding: 2px;
}
.notice_rich {
    background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
    
}
/* .sign-back-signed {
    background: linear-gradient(0deg, #75F8D2, #6096DB, #2AE3FC);
    
} */

/* .sign-back-nosign {
    background: #15131D;
} */

.sign-background-linear {
    background: linear-gradient(45deg, #75F8D2, #6096DB, #2AE3FC);
}

.sign-text-ablesign {
    color: white;
}

.sign-button-signed {
    background: #0E2357;
}

.sign-button-ablesign {
    background: #6EEB54;
}
/* :deep(p){
    color: #fff !important;
}
:deep(strong){
    color: #fff !important;
} */
</style>