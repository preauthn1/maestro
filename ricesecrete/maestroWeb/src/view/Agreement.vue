<template>
    <div class="">
        <div class="">
            <van-nav-bar left-arrow @click-left="router.go(-1)">
                <template #left>
                    <van-icon name="arrow-left" color="#FF5001" />
                </template>
                <template #title>
                    <span class="text-[11px] font-semibold">{{content?.title}}</span>
                </template>
            </van-nav-bar>
            <div class="p-[15px] rick_content">
                <div v-html="content?.content" class="content"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { AuthService } from '@/api/home';
import { onMounted, ref } from 'vue';
const router = useRouter();
const content = ref()
onMounted(async () => {
    const res = await AuthService.article_details({ type: "5" })
    content.value = res
})
</script>

<style lang="less" scoped>
.content {
    background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
    padding: 20px;
    text-wrap: wrap !important;
}

:deep(p) {
    color: #FFFFFF;
}

:deep(strong) {
    color: #FFFFFF;
}

:deep(span) {
    color: #FFFFFF;
}
</style>