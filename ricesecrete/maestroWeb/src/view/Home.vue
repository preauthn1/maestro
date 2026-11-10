<template>
    <div class="home mt-[20px]">
        <GeneralNavBar></GeneralNavBar>
        <div class="content">
            <div class="pt-[5.6rem]">
                <!-- 通知 -->
                <van-notice-bar class="notice" :text="indexdata?.notice[0]?.content.replace(/<br\s*\/?>/gi, '')"
                    @click="router.push('/notice')">
                    <template #left-icon>
                        <van-image :src="$getImageUrl('notice.png')" class="w-[20px] h-[20px]"></van-image>
                    </template>
                </van-notice-bar>
                <!-- 顶部轮播 -->
                <van-swipe v-if="indexdata?.slide.length" :autoplay="3000" :show-indicators="false" lazy-render>
                    <van-swipe-item v-for="(item, index) in indexdata?.slide" class="w-full h-[200px]" :key="index">
                        <van-image :src="item.pic" class="w-full h-full relative" alt="Image" fit="cover" />
                        <!-- <van-image :src="$getImageUrl('home/loukong.png')"
                            class="absolute bottom-0 right-0 left-0 w-full h-[40px]"></van-image> -->
                    </van-swipe-item>

                </van-swipe>
                <van-skeleton v-else>
                    <template #template>
                        <div :style="{ display: 'flex', width: '100%' }">
                            <van-skeleton-image class="w-full h-[200px]" />
                        </div>
                    </template>
                </van-skeleton>
                <div class="py-[11px] px-[11px] flex w-full justify-between gap-5">
                    <div class="gradient-border-box-fixed w-1/2" @click="router.push('/rechargechannels')">
                        <div class="flex items-center px-4 py-2  rounded-[10px]">
                            <van-image :src="$getImageUrl('home/recharge.png')" width="40px" height="auto" />
                            <span class="text-center text-[12px] font-semibold pt-2 pl-[5px]">{{ $t("资金入金")
                            }}</span>
                        </div>

                    </div>
                    <div class="gradient-border-box-fixed w-1/2" @click="router.push('/withdrawal')">
                        <div class="flex items-center px-4 py-2 rounded-[10px]">
                            <van-image :src="$getImageUrl('home/extract.png')" width="40px" height="auto" />
                            <span class="text-center text-[12px] font-semibold  pt-2 pl-[5px]">{{ $t("资金出金") }}</span>
                        </div>

                    </div>
                </div>
                <div class="flex px-[11px] w-full justify-between gap-5">
                    <div class="gradient-border-box-fixed w-1/2" @click="router.push('/newhonouredGuestentory')">
                        <div class="flex items-center px-4 py-2 rounded-[10px]">
                            <van-image :src="$getImageUrl('home/vip.png')" width="40px" height="auto" />
                            <span class="text-center text-[12px] font-semibold  pt-2 pl-[5px]">{{ $t("库存") }}</span>
                        </div>

                    </div>
                    <div class="gradient-border-box-fixed w-1/2" @click="campanhas">
                        <div class="flex items-center px-4 py-2 rounded-[10px]">
                            <van-image :src="$getImageUrl('home/tutorial.png')" width="40px" height="auto" />
                            <span class="text-center text-[12px] font-semibold  pt-2 pl-[5px]">{{ $t("活动专区") }}</span>
                        </div>

                    </div>
                </div>
                <div class="pt-5 ">
                    <div class="pl-[11px]">
                        <div class="flex items-center">
                            <div class="heng"></div>
                            <div class="text-[12px] font-semibold relat relative">{{
                                $t("数字广告中的角色") }}</div>
                        </div>

                    </div>

                    <div class="flex py-[9px] justify-between items-center px-[11px]">
                        <div class="movietype flex flex-col items-center justify-center" @click=toMoviePage(1)>
                            <van-image :src="$getImageUrl('home/4.png')" fit="contain" width="50"
                                height="auto"></van-image>
                            <div class="w-full text-[10px] font-semibold text-center">{{ $t("广告代理商") }}</div>
                        </div>
                        <div class="movietype2 flex flex-col items-center justify-center" @click=toMoviePage(2)>
                            <van-image :src="$getImageUrl('home/5.png')" fit="contain" width="50"
                                height="auto"></van-image>
                            <div class="w-full text-[10px] font-semibold text-center ">{{ $t("广告发行商") }}</div>
                        </div>
                        <div class="movietype3 flex flex-col items-center justify-center" @click=toMoviePage(3)>
                            <van-image :src="$getImageUrl('home/6.png')" fit="contain" width="50"
                                height="auto"></van-image>
                            <div class="w-full text-[10px] font-semibold text-center ">{{ $t("广告主") }}</div>
                        </div>
                    </div>
                </div>

                <div class=" mt-[19px]">
                    <div class="pl-[11px]">
                        <div class="flex items-center">
                            <div class="heng"></div>
                            <div class="text-[12px] font-semibold relat relative">{{
                                $t("MAESTRO与数字广告") }}</div>
                        </div>
                    </div>
                    <div class="px-[11px] py-[10px]">
                        <div class="swiper-box">
                            <!-- 
                                initial-slide: 开始下标
                                :pagination="swiperOption"  分页配置
                                :centered-slides="true"     居中显示
                                slides-per-view="1.8"       设置每个屏幕中同时显示的幻灯片数量为1.8。
                                :effect="'coverflow'"       设置幻灯片切换的效果为 两边小中间大
                                :coverflow-effect="coverflowEffect"  切换效果的配置
                                @swiper="onSwiperChange"：定义当切换幻灯片时触发的事件处理函数为onSwiperChange
                            -->
                            <swiper ref="swiperY" :initial-slide="1" :pagination="swiperOption" :slidesPerView='2'
                                :centered-slides="true" :effect="'coverflow'" :coverflow-effect="coverflowEffect"
                                @slide-change="onSwiperChange">
                                <template v-if="indexdata?.video.length <= 0">
                                    <swiper-slide v-for="i in 3" :key="'skeleton-' + i">
                                        <div class="home-swiper-item skeleton">
                                            <div class="bg-[#431906] h-full rounded-[10px] p-2 flex flex-col">
                                                <div class="img">
                                                    <div
                                                        class="w-full h-[130px] bg-gray-700 rounded-[10px] animate-pulse">
                                                    </div>
                                                </div>
                                                <div class="text-[12px] h-4 bg-gray-600 mt-2 rounded w-full"></div>
                                                <div class="text-[#D55012] h-3 bg-gray-600 mt-1 rounded w-3/4"></div>
                                            </div>
                                        </div>
                                    </swiper-slide>
                                </template>
                                <template v-else>
                                    <swiper-slide v-for="(item, index) in indexdata?.video" :key="item.id">
                                        <div class="home-swiper-item" @click="toMovieDetails(item.id)">
                                            <div class="bg-[#431906] h-full rounded-[10px] p-2 flex flex-col">
                                                <div class="img">
                                                    <van-image :src="item.pic" class="w-full h-[130px]" radius="10" />
                                                </div>
                                                <div
                                                    class="text-[12px] font-semibold pt-[5px] whitespace-nowrap w-full overflow-hidden text-ellipsis text-white text-center">
                                                    {{ item.name }}
                                                </div>
                                                <div class="notice_content text-[#D55012] text-[9px] font-semibold">
                                                    {{ item.description }}
                                                </div>
                                            </div>
                                        </div>
                                    </swiper-slide>
                                </template>

                                <div class="swiper-pagination" v-if="indexdata?.video.length > 1">
                                    <div class=" w-full flex justify-center my-[15px] gap-[5px]">
                                        <span
                                            :class="[`w-[15px] h-[5px]`, index == activeindex ? ' bg-[#FF6F01]' : 'bg-[#6A230B]']"
                                            v-for="(item, index) in indexdata?.video">
                                        </span>
                                    </div>
                                </div>
                            </swiper>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="pl-[11px]">
                        <div class="flex items-center">
                            <div class="heng"></div>
                            <div class="text-[12px] font-semibold relat relative">{{ $t("获奖记录") }}</div>
                        </div>
                    </div>
                    <div class="rounded-[5px] mx-[11px] my-4 gradient-border-box-fixed">
                        <van-swipe :autoplay="3000" vertical :show-indicators="false" lazy-render class="h-[50px]">
                            <van-swipe-item v-for="(item, index) in recommend_list"
                                class="w-full flex items-center justify-center h-[50px] px-[10px]" :key="index">
                                <div class="px-2 text-[12px] font-semibold text-white">{{
                                    $t("恭喜获得推广奖励", { name: item?.user_name }) }}
                                    {{ $sysCurrency }}

                                    <span class="text-[#00FF30] ">{{ item?.earnings }}</span>
                                </div>
                            </van-swipe-item>
                        </van-swipe>
                    </div>
                </div>
            </div>
        </div>

        <!-- 通告弹窗 -->
        <van-popup v-model:show="showNotice" style=" width: 100%; background-color: #FFFFFF00;">
            <div class=" w-[327px] pb-[20px] pt-0 bg-white border border-[#FF5001] mx-auto rounded-[10px]">
                <div class="w-full flex">
                    <span class="text-[15px] bg-[#FF6F01] popup-title px-[20px] py-1 font-semibold">{{ $t('站内信')
                    }}</span>
                </div>
                <div class=" w-full">
                    <van-swipe ref="noticeSwipe" class="pb-[20px]">
                        <!-- noticeList -->
                        <van-swipe-item v-for="(item, index) in noticeList" :key="index">
                            <div class="flex justify-center relative ">
                                <span
                                    class=" rounded-b-[18px] py-[7px] px-[46px] text-[17px] w-full whitespace-nowrap text-ellipsis overflow-hidden font-semibold text-[#FF5001]">{{item.title}}</span>
                            </div>
                            <div
                                class="m-[10px] p-[10px] min-h-[50px] border border-[#FF5001] rounded-[10px]">
                                <div class="h-[360px] notice_wrap overflow-auto text-black" v-html="item.content"></div>
                            </div>

                        </van-swipe-item>

                        <template #indicator="{ active, total }">
                            <div class="custom-indicator text-black">{{ active + 1 }}/{{ total }}</div>
                        </template>
                    </van-swipe>
                </div>
                <div class=" mt-[10px] flex items-center justify-around">
                    <van-button round class="appraise_border !h-[32px] min-w-[108px]" @click="showNotice = false"><span
                            class="text-white ">{{
                                $t("关闭")
                            }}</span></van-button>
                    <van-button round type="primary" class="!h-[32px] min-w-[108px]" @click="noticeNext">{{ $t("下一条")
                        }}</van-button>
                </div>
            </div>

        </van-popup>
    </div>

</template>

<script setup>
import { onMounted, ref } from 'vue';
import GeneralNavBar from '@/components/GeneralNavBar.vue';
import { useRoute, useRouter } from "vue-router";
import { AuthService } from '@/api/home';
import { showToast } from 'vant';
import { useUserStore } from '../store/userinfo';
import PubDialog from '@/components/PubDialog.vue';
// 导入 swiper 组件
import { Swiper, SwiperSlide } from 'swiper/vue';
import { useI18n } from 'vue-i18n';

// 导入 css 样式
import 'swiper/swiper.less'

// 导入 模块 关于各个模块解释在最下面
import SwiperCore, { EffectCoverflow, Navigation, Pagination, A11y, Autoplay } from 'swiper/core'

SwiperCore.use([EffectCoverflow, Navigation, Pagination, A11y, Autoplay])
const store = useUserStore();
const router = useRouter()
const route = useRoute()
const pageLoading = ref(false)
const indexdata = ref()
const { t } = useI18n()
// 定义效果配置
let coverflowEffect = {
    rotate: 0, //slide做3d旋转时Y轴的旋转角度。默认50。
    stretch: 10, //每个slide之间的拉伸值，越大slide靠得越紧。
    depth: 400, //slide的位置深度。值越大z轴距离越远，看起来越小。
    modifier: 1.3, //depth和rotate和stretch的倍率，相当于depth*modifier、rotate*modifier、stretch*modifier，值越大这三个参数的效果越明显。默认1。
    slideShadows: false //开启slide阴影。默认 true。
};
const activeindex = ref(0)
const onSwiperChange = (index) => {
    activeindex.value = index.activeIndex
}
const toMovieDetails = (id) => {
    router.push(`movieDetails?id=${id}&is_score=true`)
}

const toMoviePage = (type) => {
    router.push(`movieDetails?id=${indexdata.value.cate_videos[type].id}&is_score=true`)
}
const getdata = async () => {
    pageLoading.value = true
    const res = await AuthService.index(() => { pageLoading.value = false });
    indexdata.value = res
    pageLoading.value = false
    if (res.index.new_pop_message_count > 0) {
        await fetchNoticeList();
        showNotice.value = true;
    }
}
const recommend_list = ref([])

const showNotice = ref(false)
const noticeList = ref([])
const noticeSwipe = ref(null)
const fetchNoticeList = async () => {
    const response = await AuthService.pop_list();
    noticeList.value = response
}
const recommend = async () => {
    const res = await AuthService.vip_list()
    recommend_list.value = res

}
const noticeNext = () => {
    if (noticeSwipe.value) {
        noticeSwipe.value.next()
    }
}
const campanhas = () => {
    router.push('/activity')
    // showToast(t('当前暂无活动，请在活动期间进入。'));
}
onMounted(async () => {

    if (route.query.ic || route.query.pid || route.query.click_id) {
        localStorage.setItem("popularizar", JSON.stringify({ "ic": route.query.ic, "pid": route.query.pid, "click_id": route.query.click_id }))
    }
    if (route.query.token) {
        console.log(route.query.token)
        store.setuserInfo({ 'token': route.query.token });
    }
    getdata()
    recommend()
})



</script>
<style lang="less" scoped>
.notice_wrap>img {
    max-width: 100%;
    height: auto;
}

:deep(.notice_wrap) {
    color: black !important;

    strong {
        color: black;
    }

    p {
        color: black;
    }

    li {
        color: black;
    }

    h3 {
        color: black;
    }
}

.custom-indicator {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    bottom: 0px;
    padding: 2px 5px;
    font-size: 12px;
    background: rgba(0, 0, 0, 0.1);
}


.content {
    padding-bottom: 95px;
}


.popup-title {
    border-radius: 10px 0px 15px 0px;
}

.notice {
    background: linear-gradient(90deg, rgba(182, 61, 24, 0) 0%, rgba(80, 27, 11, 0.5) 50%, rgba(182, 61, 24, 0) 100%);
    border-radius: 0px 0px 0px 0px;
    border: 2px solid;
    margin-bottom: 10px;
    border-image: linear-gradient(90deg, rgba(255, 90, 1, 0.1), rgba(255, 183, 0, 1), rgba(255, 90, 1, 0.1)) 2 2;
}

.movietype {
    position: relative;
    width: 100px;
    height: 99px;
    background: url(../assets/images/home/1_bg.png) no-repeat top center;
    border-radius: 5px;
    // border: 1px solid #0937AD;
    background-size: contain;
}

.movietype2 {
    position: relative;
    width: 100px;
    height: 99px;
    background: url(../assets/images/home/2_bg.png) no-repeat top center;
    border-radius: 5px;
    // border: 1px solid #0937AD;
    background-size: contain;
}

.movietype3 {
    position: relative;
    width: 100px;
    height: 99px;
    background: url(../assets/images/home/3_bg.png) no-repeat top center;
    border-radius: 5px;
    // border: 1px solid #0937AD;
    background-size: contain;
}


.item {
    background: #15131E;
    border-radius: 5px;
}


//

.notice-bg {
    background-image: linear-gradient(90deg, rgba(255, 90, 1, 0.5), rgba(255, 183, 0, 0.5), rgba(255, 90, 1, 0.5));
}

.appraise_border {
    border-radius: 6px;
    background: linear-gradient(89deg, #FF6E35 0%, #FFAD72 100%)
}



.notice_content {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    /* 添加这一行 */
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: break;
}

.sector {
    width: 100%;
    height: 40%;
    transform-origin: 50% 100%;
    display: flex;
    justify-content: center;
    align-items: flex-start;
}

.wheel {
    transform-origin: center;
    transition: transform 3s cubic-bezier(0.25, 0.1, 0.25, 1);
}

::-webkit-scrollbar {
    display: none;
}

.home-swiper-item {
    background-image: linear-gradient(270deg, rgba(255, 80, 1, 1), rgba(255, 162, 1, 1), rgba(255, 80, 1, 1));
    width: 160px;
    min-height: 80px;
    padding: 2px;
    border-radius: 10px;
}



.gradient-border-box-fixed {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background-color: rgba(255, 255, 255, 0.0);
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
.gradient-border-box-fixed::before {
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
    padding: 2px;
    /* 调整此值来控制边框的粗细 */
    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    /* 用于裁剪内部 */
    mask-composite: exclude;
    /* 排除掉中间的部分，只留下边框 */
    -webkit-mask-composite: exclude;
    /* Safari 兼容 */
}

/* 骨架屏动画 */
@keyframes shimmer {
    0% {
        opacity: 0.5;
    }

    50% {
        opacity: 1;
    }

    100% {
        opacity: 0.5;
    }
}

.skeleton .img,
.skeleton div:not(.home-swiper-item) {
    animation: shimmer 1.5s infinite;
}

.home-swiper-item.skeleton {
    background: #2d2d2d;
    /* 骨架屏背景色 */
}
</style>