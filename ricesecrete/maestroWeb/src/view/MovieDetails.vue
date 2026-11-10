<template>
    <div class=" pb-[60px]">
        <van-nav-bar fixed left-arrow @click-left="router.go(-1)"
            :style="{ 'background-color': 'transparent!important' }" />
        <video controls playsinline autoplay :src="detail?.video?.video_url" @play="onPlay" @pause="onPause"
            @ended="onEnded" class="w-full"></video>
        <div class=" pt-[20px] px-[16px]">
            <div class=" text-[14px]">{{ detail?.video?.name }}</div>
            <div class="mb-[20px] text-[12px] font-medium">
                <span class="">{{ $t("描述") }}：</span>{{ detail?.video?.description }}
            </div>

        </div>

    </div>
</template>

<script setup>
import { onMounted, ref ,onUnmounted} from "vue";
import { useRoute, useRouter } from "vue-router";
import { AuthService } from '@/api/home';
import { usevipStore } from "../store/vip";
import { showToast } from "vant";
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter()
const route = useRoute()
const store = usevipStore()
const detail = ref()
const value = ref(0)
const recommend_video_list = ref([])
const videoType = ["", t("预告片"), t("音乐"), t("商品广告")]
const score = ref(false)
const scored = ref(false)
const getdetail = async (id) => {
    const res = await AuthService.video_detail({ "id": id })
    detail.value = res
}

const getrecommend_video_list = async (id) => {
    const res = await AuthService.recommend_video_list({ "id": id })
    recommend_video_list.value = res
}
const onChange = async (value) => {
    console.log(store.choose_level_id)
    const res = await AuthService.addVideoTask({ 'level_id': store?.choose_level_id, 'video_id': route.query?.id })
    scored.value = true;
    showToast(t("任务完成"))
}

/* 点击推荐视频时加载当前页面 */
const reload = (id) => {
    if (score.value) {
        window.location.replace(`#/movieDetails?id=${id}&is_score=true`);
    } else {
        window.location.replace(`#/movieDetails?id=${id}`);
    }

    location.reload();
}

const countdownShow = ref(true)
const timer = ref(20)
let countdownInterval = null;

const startCountdown = () => {
    if (!countdownInterval) {
        countdownInterval = setInterval(() => {
            if (timer.value > 0) {
                timer.value--;
            } else {
                clearInterval(countdownInterval);
                countdownShow.value = false; // 隐藏倒计时
            }
        }, 1000);
    }
};

// 暂停倒计时
const pauseCountdown = () => {
    if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
    }
};

const onPlay = () => {
    if (!countdownShow) return
    startCountdown()
}

const onPause = () => {
    if (!countdownShow) return
    pauseCountdown();
};

const onEnded = () => {
    pauseCountdown();
    countdownShow.value = false;
};

onMounted(() => {
    if(!route.query.id){
        router.push("/")
    }
    if (route.query?.is_score) {
        score.value = true
    }
    getdetail(route.query.id)
    getrecommend_video_list(route.query.id)
})

onUnmounted(() => {
    pauseCountdown();
});

</script>

<style scoped>

.movie_item {
    width: calc((100% - 2rem)/3);
    min-height: 141px;
}

.movie_item>img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.van-rate--disabled{
    filter: grayscale(100%);
}
</style>