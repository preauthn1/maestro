<template>
    <div class="content px-[16px]">
        <van-nav-bar fixed left-arrow :title="$t('登录密码')" @click-left="router.go(-1)">
            <template #left>
                <van-icon name="arrow-left" color="#FF5001" />
            </template>
            <template #title>
                <span class="text-[11px] font-semibold">{{ $t('登录密码') }}</span>
            </template>
        </van-nav-bar>
        <div class=" pt-[78px]">
            <van-cell-group inset :border="false">
                <div class="gradient-border-box-fixed-leiji my-[20px]">
                    <van-field v-model="passwordFrom.oldpassword" :maxlength="20" type="password" class="border-none mb-0"
                        :placeholder="$t('请输入登录密码')" />
                </div>
                <div class="gradient-border-box-fixed-leiji mb-[20px]">
                    <van-field v-model="passwordFrom.password" :maxlength="20" type="password" class="border-none mb-0"
                        :placeholder="$t('请输入新的登录密码')" />
                </div>
                <div class="gradient-border-box-fixed-leiji mb-[20px]">
                    <van-field v-model="passwordFrom.repassword" :maxlength="20" type="password" class="border-none mb-0"
                        :placeholder="$t('请再次确认密码')" />
                </div>


            </van-cell-group>
        </div>
        <div class=" mt-[96px]">
            <van-button round block size="large" type="primary" @click="changePassword">
                <span class="text-[13px] font-semibold text-white">{{ $t("确认") }}</span>
            </van-button>
        </div>

    </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useRouter } from "vue-router";
import { AuthService } from "@/api/home";
import { showToast } from "vant";
import { useUserStore } from "@/store/userinfo";
import { useI18n } from 'vue-i18n';
const { t } = useI18n();
const router = useRouter()
const store = useUserStore()
const passwordFrom = reactive(
    {
        oldpassword: "",
        password: "",
        repassword: ""
    }
)
const changePassword = async () => {
    if (!passwordFrom.oldpassword) {
        return showToast(t("请输入登录密码"))
    }
    if (!passwordFrom.password) {
        return showToast(t("请输入新的登录密码"))
    }
    if (!passwordFrom.repassword) {
        return showToast(t("请再次确认登录密码"))
    }
    await AuthService.change_password({ ...passwordFrom })
    showToast(t("修改成功,请重新登录"))
    setTimeout(() => {
        store.clearUserInfo()
        router.push("/login")
    }, 300)
}
</script>

<style lang="less" scoped>
::v-deep(.van-cell-group--inset) {
    margin: 0 !important;
}

::v-deep(.van-cell) {
    padding: 17px 11px !important;

    &::after {
        content: none;
    }
}

.van-button--default {
    border: none !important;
    border-radius: 13px !important;
    background: linear-gradient(180deg, rgba(0, 122, 255, 1), #006BE8);
    box-shadow: 0px 1px 2px 0px rgba(15, 9, 13, 0.1);
    border-radius: 18px;
}

.content {
    background: url("../assets/images/home/home_bg.png") no-repeat top center;
    background-size: var(--maxWidth) auto;
    min-height: 100vh;
    background-attachment: fixed;
}

.gradient-border-box-fixed-leiji {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background: rgba(34, 37, 40, 0.4);
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