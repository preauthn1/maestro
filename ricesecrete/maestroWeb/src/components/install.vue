<template>
    <div>
        <van-popup v-model:show="props.showCenter" :close-on-click-overlay="false" @click-overlay="emiter" class="px-[43px] w-full bg-[#00000000]">
            <div class="bg-white rounded-[10px] border border-[#FF5001] ">
                <div class="flex">
                    <span
                        class="px-[20px] py-1 bg-[#FF5001] text-[12px] font-medium text-white rounded-tl-[10px] rounded-br-[15px]">{{
                            $t("下载APP") }}</span>
                </div>
                <div class="mt-[40px] text-black px-[30px]">
                    <van-button type="primary" size="large" class="!h-[35px] my-[10px]"
                        style="background: linear-gradient( 89deg, #014DFF 0%, #01B7FF 100%) !important;"
                        @click="inStall">
                        <span class="text-white text-[14px] font-semibold">{{ $t("下载Maestro APP") }}</span>
                    </van-button>
                    <van-button type="primary" size="large" class="!h-[35px] my-[10px]" @click="mtInstall">
                        <span class="text-white text-[14px] font-semibold">{{ $t("下载MT APP") }}</span>
                    </van-button>
                </div>
                <div class="flex justify-center px-[45px] pt-[30px]">
                    <van-button type="primary" size="large" @click="emiter" class="!h-[35px] my-[10px]">
                        <span class="text-white text-[14px] font-semibold">{{ $t("关闭") }}</span>
                    </van-button>
                </div>
            </div>
        </van-popup>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useSystemStore } from "../store/system";
const store = useSystemStore()
const props = defineProps({
  showCenter: {
    type: Boolean,
    default: false
  },
});

const emit = defineEmits(['onClose']);

function detectMobileType() {
  const userAgent = navigator.userAgent || navigator.vendor || window.opera;
  // 判断是否为 iOS
  if (/iPhone|iPad|iPod/i.test(userAgent)) {
    return 'iOS';
  }
  // 判断是否为 Android
  if (/Android/i.test(userAgent)) {
    return 'Android';
  }
  // 其他类型
  return 'Unknown';
}

const inStall = () => {

  if (detectMobileType() == 'iOS') {
    // window.location.href = store.disposition.site_info.app_ios_download_url
     window.open(store.disposition.site_info.app_ios_download_url, '_blank')
  } else {
    window.location.href = store.disposition.site_info.app_android_download_url
  }

};

const mtInstall = () => {
     if (detectMobileType() == 'iOS') {
    // window.location.href = store.disposition.site_info.app_mt_ios_download_url
     window.open(store.disposition.site_info.app_mt_ios_download_url, '_blank')
  } else {
    window.location.href = store.disposition.site_info.app_mt_android_download_url
  }
}
const emiter = () => {
    emit('onClose', false)
}
</script>