<template>
    <van-dialog v-model:show="props.showDialog" :title="$t('温馨提示')" :close-on-click-overlay="false" show-cancel-button
        :confirmButtonText="$t('是')" :cancelButtonText="$t('否')" cancel-button-color="#000"
        confirm-button-color="#ee0a24">
        <template #default>
            <div class="py-[16px] px-[16px] text-center">
                <span class="text-[#000] text-[14px]">{{ $t('预算内库存以售空，是否需要平台IA快速协助竞拍更高价格') }}</span>
            </div>
        </template>
        <template #footer>
            <div class="flex items-center w-full" @click="cancelAction">
                <div class="flex-1 text-center bg-[#eee] py-[10px]" v-if="props.showCancel">
                    <span class="text-[#999] text-[14px] font-medium">{{ props.cancelText }}</span>
                </div>
                <div class="flex-1 text-center" @click="confirmAction">
                    <span class="text-[#000] text-[14px] font-bold">{{ props.confirmText }}</span>
                </div>
            </div>
        </template>

    </van-dialog>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from "vue";
import { useI18n } from 'vue-i18n';
const { t } = useI18n();

const props = defineProps({
    showDialog: {
        type: Boolean,
        default: true
    },
    confirmText: {
        type: String,
        default: null
    },
    showCancel: {
        type: Boolean,
        default: false
    },
    cancelText: {
        type: String,
        default: null
    }
});

const confirmText = props.confirmText || t('是');
const cancelText = props.cancelText || t('否');

const confirmAction = () => {

}
const cancelAction = () => {

}
</script>

<style scoped>
:deep(.van-dialog) {
    top: 45%;
    width: var(--van-dialog-width);
    overflow: hidden;
    font-size: var(--van-dialog-font-size);
    background: linear-gradient(180deg, #C3DCFF, #FFFFFF);
    border-radius: var(--van-dialog-radius);
    backface-visibility: hidden;
    transition: var(--van-dialog-transition);
    transition-property: transform, opacity;
}
</style>