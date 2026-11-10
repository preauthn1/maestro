<template>
    <div class="">
        <GeneralNavBar />
        <div class=" min-h-[100vh] pb-[100px]">
            <div class="pt-[5.6rem]">
                <div class=" pt-[1rem] relative z-[2]">
                    <img :src="$getImageUrl('invitation_top_icon.png')" alt="">
                </div>
            </div>
            <div class="bottom-content mx-[11px]">
                <div class="pt-[15px] px-[12px]">
                    <span class="text-[13px] font-bold text-[#FF5001]">{{ $t("邀请链接") }}</span>
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col items-start">
                            <span class="text-[11px] font-medium text-[#FF5001] ">{{
                                store.userInfo?.user_info?.share_link }}
                                <van-icon :name="$getImageUrl('icon_copy_black.png')" class="left-[3px]"
                                    @click="$copy(store.userInfo?.user_info?.share_link, t)"></van-icon>
                            </span>
                            <div class="flex ">
                                <van-image fit="cover" round width="62.5" class="shrink-0" height="62.5"
                                    :src="store.userInfo?.user_info?.avatar" />
                                <div class="flex flex-col pl-[8px] justify-center">
                                    <!-- <span class="text-[13px] font-medium">{{store.userInfo?.user_info?.mobile}}</span> -->
                                    <div>
                                        <span class="text-[11px] font-medium text-black">{{ $t("邀请代码") }}：{{
                                            store.userInfo?.user_info?.invite_code }}
                                            <van-icon :name="$getImageUrl('icon_copy_black.png')" class="left-[3px]"
                                                @click="$copy(store.userInfo?.user_info?.invite_code, t)"></van-icon>
                                        </span>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center flex flex-col items-center">
                            <div class="w-[92px] h-[92px]">
                                <qcord :url="store.userInfo?.user_info?.share_link" class="rounded-[5px]"></qcord>
                            </div>
                            <span class="text-[10px] ml-[-10px] mr-[-10px] font-medium text-black">{{ $t("点击保存二维码")
                                }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-background mx-[11px] pt-[15px] pb-[30px]" v-if="content">
                <div v-html="content?.content" class="px-[23px]"></div>
            </div>
        </div>

    </div>
</template>

<script setup>
import GeneralNavBar from "@/components/GeneralNavBar.vue"
import qcord from "@/components/qcord.vue";
import { useUserStore } from "@/store/userinfo";
import { AuthService } from "@/api/home";
import { onMounted, ref } from "vue";
import { useI18n } from 'vue-i18n';
const { t } = useI18n()
const store = useUserStore();
const content = ref()
onMounted(async () => {
    store.getUserInfo();
    const res = await AuthService.article_details({ type: "6" })
    content.value = res
})
</script>

<style scoped>
.invite_nav_bg {
    --van-nav-bar-background: #00000000;
}

.bottom-content {
    margin-top: -40px;
    background: url("../assets/images/invitation_bottom_icon.png") no-repeat;
    background-size: cover;
    position: relative;
    min-height: 180px;
    z-index: 0;
}

.content-background {
  background: #FFFFFF;
  border-radius: 0px 0px 20px 20px;
}

.content-background p {
    color: #FF0000;
    /* 你想要的颜色 */
}

:deep(h3) {
    color: #000000;
}
:deep(strong) {
  color: #000000;
}

:deep(p) {
  color: #000000;
}

:deep(li){
  color: #000000;
}

</style>