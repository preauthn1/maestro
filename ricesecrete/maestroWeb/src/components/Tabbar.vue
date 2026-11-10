<template>
    <div class="">
        <van-tabbar route v-model="active" style="z-index: 999; color: #fff; font-size: 12px;">
            <van-tabbar-item replace to="/">
                <span>{{ $t("首页") }}</span>
                <template #icon="props">
                    <img class=" w-[30px] h-[auto!important]" :src="props.active ? $getImageUrl('icon_home_ac.png') : $getImageUrl('icon_home.png')" />
                </template>
            </van-tabbar-item>
            <van-tabbar-item  replace to="/upgrade">
                <span>{{ $t("团队") }}</span>
                <template #icon="props">
                    <img class=" w-[30px] h-[auto!important]" :src="props.active ? $getImageUrl('icon_upgrade_ac.png') : $getImageUrl('icon_upgrade.png')" />
                </template>
            </van-tabbar-item>
            <van-tabbar-item  replace to="/newhonouredGuestentory">
                <span>{{ $t("库存") }}</span>
                <template #icon="props">
                    <img class=" w-[30px] h-[auto!important]" :src="props.active ? $getImageUrl('icon_guest_ac.png') : $getImageUrl('icon_guest.png')" />
                </template>
            </van-tabbar-item>
            <van-tabbar-item  replace to="/invitation">
                <span>{{ $t("推广") }}</span>
                <template #icon="props">
                    <img class=" w-[30px] h-[auto!important]" :src="props.active ? $getImageUrl('icon_invite_ac.png') : $getImageUrl('icon_invite.png')" />
                </template>
            </van-tabbar-item>
            <van-tabbar-item  replace to="/profile">
                <span>{{ $t("我的") }}</span>
                <template #icon="props">
                    <img class=" w-[30px] h-[auto!important]" :src="props.active ? $getImageUrl('icon_profile_ac.png') : $getImageUrl('icon_profile.png')" />
                </template>
            </van-tabbar-item>
        </van-tabbar>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const active = ref(0);
</script>

<style lang="less" scoped>

</style>