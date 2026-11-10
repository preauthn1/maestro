<template>

  <van-nav-bar fixed :style="navbarStyle" z-index="10" @click-right="showCenter = true">
    <template #left v-if="showLogo">
      <div class="text-[16px]">
        <img :src="store.disposition?.site_info?.site_logo" class="w-auto h-[46px]" alt="">
      </div>
    </template>
    <template #right>
      <div class=" flex items-center gap-[26px]" v-if="!isMobil">
        <van-image contain class=" w-[20px] h-auto" :src="$getImageUrl('icon_down.png')" />
      </div>
    </template>
  </van-nav-bar>

  <Install v-model:showCenter="showCenter" @on-close="showCenter = false" />

</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from "vue";
import { useSystemStore } from "../store/system";
import Install from "./install.vue";
const store = useSystemStore()
const showCenter = ref(false);
const navbarStyle = ref({ 'background': 'rgba(0,0,0,0)!important' })
const windowScroll = () => {
  let scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
  if (scrollTop < 50) {
    navbarStyle.value = { 'background': 'rgba(0,0,0,0)!important' }
  } else {
    navbarStyle.value = { 'background': 'linear-gradient(180deg, #0F1116, #1C1413)!important' }
  }
  // let opacity = Math.abs(Math.round(scrollTop)) / 250;
  // console.log(opacity,'111111111')
  // navbarStyle.value = {'background-color': `rgba(0,0,0,${opacity})!important`}
}
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
    window.location.href = store.disposition.site_info.app_ios_download_url
  } else {
    window.location.href = store.disposition.site_info.app_android_download_url
  }

};
const userAgent = navigator.userAgent.toLowerCase();
const deviceType = ref('');
if (window.navigator.standalone) {
  deviceType.value = 'IPHONE';
} else {
  const userAgent1 = navigator.userAgent || navigator.vendor;
  if (/iPhone|iPad|iPod/.test(userAgent1) && !/Safari/.test(userAgent1)) {
    deviceType.value = 'IPHONE';
  } else if (/iPhone|iPad|iPod/.test(userAgent) && window.innerWidth === screen.width &&
    window.innerHeight === screen.height) {
    deviceType.value = 'IPHONE';
  } else {
    if (userAgent.includes("bl999.andriod")) {
      deviceType.value = 'ANDROID';
    } else {
      deviceType.value = 'WINDOWS';
    }
  }
}
const isMobil = ref(deviceType.value != 'WINDOWS');
onMounted(() => {
  window.addEventListener('scroll', windowScroll)
})

onBeforeUnmount(() => {
  window.removeEventListener('scroll', windowScroll)
})

const props = defineProps({
  showLogo: {
    type: Boolean,
    default: true
  },
  showBack: {
    type: Boolean,
    default: true
  }
});
</script>

<style scoped></style>