<template>
    <div class="newvip mt-[20px]">
        <GeneralNavBar />
        <div class="">
            <div class="pt-[5.6rem] pb-[100px] " v-if="is_loding">
                <!--  -->
                <div v-if="is_vip">
                    <div class="gradient-border-box-small mx-[11px]">
                        <div class="mt-2  px-4 py-10 flex justify-between rounded-[15px] ">
                            <div class="flex flex-col items-center w-1/2 border-r border-[#fff]">
                                <div class="text-[12px]  font-medium text-center">{{ $t("今日广告利润") }}</div>
                                <div class="text-[#00FF30] text-[14px] mt-2 font-bold">{{
                                    store?.taskInfo?.user_income?.today_income
                                    }}</div>
                            </div>
                            <div class="flex flex-col items-center w-1/2">
                                <div class="text-[12px] font-medium text-center">{{ $t("累计广告利润") }}</div>
                                <div class="text-[#00FF30] text-[14px] mt-2 font-bold">{{
                                    store?.taskInfo?.user_income?.total_income
                                    }} </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-[23px] pt-[5px]" v-if="Number(store?.taskInfo?.task_count?.task_reset_time) > 0">
                        <div class="my-4 text-[14px] font-semibold text-center">{{ $t("剩余购买时间") }}</div>
                        <div class="mt-[5px] flex justify-center">
                            <van-count-down :time="Number(store?.taskInfo?.task_count?.task_reset_time) * 1000 | 0">
                                <template #default="timeData">
                                    <span class="block">{{ padZero(timeData.hours) }}</span>
                                    <span class="colon">:</span>
                                    <span class="block">{{ padZero(timeData.minutes) }}</span>
                                    <span class="colon">:</span>
                                    <span class="block">{{ padZero(timeData.seconds) }}</span>
                                </template>
                            </van-count-down>
                        </div>
                    </div>

                    <div class=" flex flex-col items-center mt-6 mx-[23px]"
                        :class="item?.type == 4 ? 'jinx' : item?.type == 1 ? 'showroom' : item?.type == 2 ? 'Placements' : item?.type == 3 ? 'promotion' : ''"
                        v-for="item, index in store?.taskInfo?.level_list">

                        <!-- <div v-if="item?.type == 2" class=" bg-[#FFCC00] px-4 py-1 text-center jinxabs text-[#EC1C00]">
                            {{
                                $t('精选优质库存') }}</div> -->
                        <div class="w-full py-[10px]">
                            <div class="px-[21px]">
                                <div class="text-[13px] font-bold ">{{
                                    item?.level_name }}
                                </div>
                                <div class="py-2 flex justify-between items-center border-b"
                                    :class="[item.type == 4 ? 'border-[#E8460E]' : item.type == 2 ? 'border-[#00296F]' : item.type == 1 ? 'border-[#0067C1]' : item.type == 3 ? 'border-[#008037]' : '']">
                                    <div class="flex flex-col items-center gap-2 w-1/2">
                                        <div class="text-[10px] font-medium">{{ $t('起步价格') }}</div>
                                        <div class="text-[10px]  font-bold text-[#FFFB00]">{{ item?.price_text ?
                                            item?.price_text :
                                            item?.price }}</div>
                                    </div>
                                    <div class="flex flex-col items-center gap-2 w-1/2">
                                        <div class="text-[10px] font-medium">{{ $t('开放对象') }}</div>
                                        <div class="text-[10px]  font-bold text-[#FFFB00]">{{
                                            item?.limit_unlock_num_text }}</div>
                                    </div>
                                </div>
                                <div class="py-2 flex justify-between items-center gap-2">
                                    <div class="flex flex-col items-center w-1/2">
                                        <div class="text-[10px] font-medium">{{ $t('预期利润率') }}</div>
                                        <div class="text-[10px] font-bold text-[#FFFB00]">{{ item?.income_text }} </div>
                                    </div>
                                    <div class="flex flex-col items-center w-1/2">
                                        <div class="text-[10px] font-medium">{{ $t('推广要求') }}</div>
                                        <div class="text-[10px] font-bold text-[#FFFB00]">{{ item?.level_time_text }}
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="p-[15px]">
                                <!-- <van-button class="w-full bg-transparent rounded-[15px]"
                                    v-if="item.left_num <= 0">$t("余额不足，无法进入")   $t("当前库存已售完，请查看其他价格选项") 
                                    <span class="text-[14px] font-bold">{{ $t("当前库存已售完，请查看其他价格选项") }}</span>
                                </van-button> -->
                                <van-button class="w-full bg-transparent"
                                    @click="unlock(item?.level_id, item.is_can_buy, item?.status, item?.type)"><!--$t("余额不足，无法进入")   $t("当前库存已售完，请查看其他价格选项") -->
                                    <span class="text-[11px] font-semibold text-[#ffffff]">{{ $t("进入购买") }}</span>
                                </van-button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-[20px] px-[23px]" v-else>
                    <div class="gradient-border-box-small mt-2 px-2 py-[20px]">
                        <div class="text-[#fff] text-center text-[14px] font-bold">{{ $t("MAESTRO平台的规模") }}
                        </div>
                        <div class="text-right text-[10px] text-[#FFFFFF] mt-[6px]">{{ scale?.deadline }}</div>
                        <div class="flex justify-between items-center w-full mt-[10px]">
                            <div class="flex flex-col justify-center w-1/2 line">
                                <div class="flex justify-center flex-col items-center mb-[20px] mt-[2px]">
                                    <div class=" text-[#FFFB00] text-[16px] font-bold">
                                        {{ scale?.daily_impressions }}
                                    </div>
                                    <div class="text-[#FFFFFF] text-center text-[10px] font-medium mt-[3px]">
                                        {{ $t('每日广告展示次数') }}
                                    </div>
                                </div>
                                <div class="flex justify-center flex-col items-center mb-[20px]">
                                    <div class=" text-[#FFFB00] text-[16px] font-bold">{{ scale?.office_locations }}
                                    </div>
                                    <div class="text-[#FFFFFF] text-center text-[10px] font-medium">{{ $t('办公地点') }}
                                    </div>
                                </div>
                                <div class="flex justify-center flex-col items-center">
                                    <div class=" text-[#FFFB00] text-[16px] font-bold">{{ scale?.direct_publishers
                                        }}
                                    </div>
                                    <div class="text-[#FFFFFF] text-center text-[10px] font-medium">{{ $t('直接发行商')
                                        }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col justify-center w-1/2">
                                <div class="flex justify-center flex-col items-center mb-[20px]">
                                    <div class=" text-[#FFFB00] text-[16px] font-bold">{{ scale?.daily_data_volume
                                        }}
                                    </div>
                                    <div class="text-[#FFFFFF] text-center text-[10px] font-medium">{{
                                        $t('每日处理的数据量') }}
                                    </div>
                                </div>
                                <div class="flex justify-center flex-col items-center mb-[20px]">
                                    <div class=" text-[#FFFB00]  text-[16px] font-bold">
                                        {{ scale?.advertiser_bid_amount }}</div>
                                    <div class="text-[#FFFFFF] text-center text-[10px] font-medium">{{ $t('广告主投标金额')
                                        }}
                                    </div>
                                </div>
                                <div class="flex justify-center flex-col items-center ">
                                    <div class=" text-[#FFFB00] text-[16px] font-bold">{{ scale?.data_centers }}
                                    </div>
                                    <div class="text-[#FFFFFF] text-center text-[10px] font-medium">{{ $t('数据中心') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="institution pt-[8px] pb-[23px] px-[15px] mt-[25px]">
                        <div class="flex justify-between items-center border-b-2 py-[15px] border-b-[#FFFFFF66] px-[28px] relative"
                            @click="selectedIndex = 0">
                            <div class="text-[13px] flex-1 font-semibold text-center">{{ $t('机构广告代理') }}</div>
                            <img :src="$getImageUrl(selectedIndex == 0 ? 'slices_ac.png' : 'slices.png')"
                                class="ml-7 w-[15px] h-[15px] object-contain absolute right-[28px]" alt="">
                        </div>
                        <div class="flex justify-between items-center py-[15px] px-[28px] relative"
                            @click="selectedIndex = 1">
                            <div class="text-[13px] flex-1 font-semibold text-center">{{ $t('直销广告代理') }}</div>
                            <img :src="$getImageUrl(selectedIndex == 1 ? 'slices_ac.png' : 'slices.png')"
                                class="ml-7 w-[15px] h-[15px] object-contain absolute right-[28px]" alt="">
                        </div>
                        <button class="institution_btn w-full" @click="changebutton(1)">
                            <span class="text-[11px] font-semibold text-[#ffffff]">{{ $t("点击获取编码") }}</span>
                        </button>
                    </div>
                    <div class="institution1 pt-[8px] pb-[23px] px-[15px] mt-[40px]">
                        <div
                            class="flex justify-between items-center border-b-2 py-[15px] border-b-[#FFFFFF66] px-[28px]">
                            <div class="text-[13px] flex-1 font-semibold text-center">{{ $t('广告库存') }}</div>
                        </div>
                        <div class="flex justify-between items-center py-[15px] px-[28px]">
                            <div class="text-[13px] flex-1 font-semibold text-center">{{ $t('机构广告代理') }}</div>
                        </div>
                        <button class="institution_btn1 w-full" @click="changebutton(2)">
                            <span class="text-[11px] font-semibold text-[#ffffff]">{{ $t("点击进入") }}</span>
                        </button>
                    </div>
                    <div class="institution2 pt-[8px] pb-[23px] px-[15px] mt-[40px]">
                        <div
                            class="flex justify-between items-center border-b-2 py-[15px] border-b-[#FFFFFF66] px-[28px]">
                            <div class="text-[13px] flex-1 font-semibold text-center">{{ $t('广告库存') }}</div>
                        </div>
                        <div class="flex justify-between items-center py-[15px] px-[28px]">
                            <div class="text-[13px] flex-1 font-semibold text-center">{{ $t('直销广告代理') }}</div>
                        </div>
                        <button class="institution_btn2 w-full" @click="changebutton(3)">
                            <span class="text-[11px] font-semibold text-[#ffffff]">{{ $t("点击进入") }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <van-popup v-model:show="showCenter" round :close-on-click-overlay="false"
            :style="{ padding: '20px', 'padding-top': '10px', 'width': '80%', 'max-width': '42.5rem', background: '#FFFFFF', border: '1px solid #FF5001' }">
            <div class="w-full">
                <span class="w-[24px] h-[24px] object-contain absolute right-3 top-3" @click="cancelBtn">
                    <svg t="1751689945299" class="icon" viewBox="0 0 1024 1024" version="1.1"
                        xmlns="http://www.w3.org/2000/svg" p-id="4872" width="20" height="20">
                        <path
                            d="M850.538343 895.516744c-11.494799 0-22.988574-4.386914-31.763424-13.161764L141.103692 204.669426c-17.548678-17.534352-17.548678-45.992497 0-63.525825 17.548678-17.548678 45.977147-17.548678 63.525825 0l677.671227 677.685553c17.548678 17.534352 17.548678 45.992497 0 63.525825C873.526917 891.128807 862.032118 895.516744 850.538343 895.516744z"
                            fill="#FF5001" p-id="4873" data-spm-anchor-id="a313x.search_index.0.i4.7a3c3a818FznJz"
                            class="selected"></path>
                        <path
                            d="M172.867116 895.516744c-11.494799 0-22.988574-4.386914-31.763424-13.161764-17.548678-17.534352-17.548678-45.992497 0-63.525825l677.671227-677.685553c17.548678-17.548678 45.977147-17.548678 63.525825 0 17.548678 17.534352 17.548678 45.992497 0 63.525825L204.629517 882.354979C195.85569 891.128807 184.360891 895.516744 172.867116 895.516744z"
                            fill="#FF5001" p-id="4874"></path>
                    </svg>
                </span>
                <div class="" v-if="curbutton == 1">
                    <div class="text-[#FF5001] text-[16px] font-bold text-center">{{ $t("访问未授权") }}</div>
                    <img :src="$getImageUrl('an.png')" class="w-[60px] h-auto mx-auto mt-[15px]" alt="">
                    <div class="flex justify-center pt-[13px]">
                        <van-button class="px-[24px] rounded-[6px]" style="background: linear-gradient( 89deg, #FF4901 0%, #FF6F01 100%);" @click="showCenter = false">
                            <span class="text-[13px] letter font-semibold">{{ $t("确认") }}</span>
                        </van-button>
                    </div>
                </div>
                <div class="" v-else>
                    <img :src="$getImageUrl('an.png')" class="w-[60px] h-auto mx-auto mb-[20px]" alt="">
                    <div class="px-[15px]">
                        <van-field v-model="number" type="number" maxlength="6" @update:model-value="is_error = false"
                            :placeholder="$t('请填写您的编码')" />
                    </div>

                    <div class="flex items-center px-[15px]" v-if="is_error">
                        <img :src="$getImageUrl('mistake.png')" class="w-[18px] h-[18px] object-contain" alt="">
                        <span class="text-[#FF2508] pl-2 font-semibold text-[10px]">{{ $t('编码错误') }}</span>
                    </div>
                    <div class="p-4 px-6">
                        <div class="px-[15px]">
                            <van-button class="w-full bg-[#007AFF] rounded-[6px]" @click="handlePassword">
                                <span class="text-[13px] font-semibold">{{ $t("确认") }}</span>
                            </van-button>
                            <van-button class="w-full cancel_btn mt-4 " @click="cancelBtn">
                                <span class="text-[13px] font-semibold">{{ $t("取消") }}</span>
                            </van-button>
                        </div>

                    </div>
                </div>
            </div>
        </van-popup>
        <van-popup v-model:show="showRule" class="px-[30px] w-full bg-[#00000000]">
        <div class="bg-white rounded-[10px] border border-[#FF5001] ">
            <img :src="$getImageUrl('prop_icon.png')" class="w-[60px] h-[60px] mx-auto mt-[20px]" alt="">
            <div class="m-[10px] p-[10px] rounded-[10px]">
                <div class=" text-black text-[12px] font-semibold">{{sysstore.disposition.setting.repayment_reminder_content}}</div>
            </div>
            <div class="flex justify-center px-[50px]">
                <van-button type="primary" size="large" @click="showRule = false" class="!h-[34px] my-[10px] mb-[20px]">
                    <span class="text-white text-[14px] font-semibold">{{ $t("确认") }}</span>
                </van-button>
            </div>
        </div>
    </van-popup>
    </div>
</template>

<script setup>
import GeneralNavBar from "@/components/GeneralNavBar.vue"
import { usevipStore } from "../store/vip";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import { AuthService } from '@/api/home';
import { showToast } from "vant";
import { useUserStore } from "@/store/userinfo";
import { useI18n } from "vue-i18n";
import { useSystemStore } from "../store/system";
const { t } = useI18n();
const usestore = useUserStore()
const sysstore = useSystemStore()
const showRule = ref(false)
const router = useRouter()
const store = usevipStore();
const showCenter = ref(false)
const curbutton = ref(0)
const selectedIndex = ref(0);
const is_vip = ref(false)
const number = ref()
const is_error = ref(false)
const is_loding = ref(false)
function padZero(number) {
    return number < 10 ? '0' + number : number.toString();
}
const changebutton = (num) => {
    curbutton.value = num
    showCenter.value = true
}

const unlock = async (num, is_buy, status, type) => {
    //精选库存
    if (type == 4) {
        usestore.set_is_precision(2)
        usestore.set_currentType(type)
        //体验区
    } else if (type == 1) {
        usestore.set_is_precision(3)
        usestore.set_currentType(type)
    }
    //普通
    else {

        usestore.set_is_precision(1)
        usestore.set_currentType(type)
    }
    router.push(`/financialcenter?id=${num}`)
}
const handlePassword = async () => {
    if (curbutton.value == 2) {
        //当前时间戳秒数
        setTimeout(() => {
            is_error.value = true;
        }, 1000);
        return showToast($t('编码错误'))
    }
    if (!number.value) {
        return showToast(t('请输入编码'))
    }

    const res = await AuthService.checkPassword({ 'password': number.value })
    if (res.code == 0) {
        let curtime = Math.floor(Date.now() / 1000);
        usestore.encode.bianma = number.value
        usestore.encode.expire_time = curtime + res.time_left
        showCenter.value = false
        is_vip.value = true
        if(store?.taskInfo?.user_income?.user_money < 0 && sysstore.disposition.setting.repayment_reminder_status == 1){
            if(usestore.is_show_balance_warning == 0){
                showRule.value = true
                usestore.is_show_balance_warning =curtime + store?.taskInfo?.task_count?.task_reset_time
            }else{
                
                if(curtime > usestore.is_show_balance_warning){
                    showRule.value = true
                    usestore.is_show_balance_warning =curtime + store?.taskInfo?.task_count?.task_reset_time
                }
            }
            
        }
        
    } else {
        //当前时间戳秒数
        is_error.value = true;
        showToast(res.msg)
    }



}
const scale = ref({})
const handlescale = async () => {
    const res = await AuthService.scale_index()
    scale.value = res

}
const cancelBtn = async () => {
    showCenter.value = false;
    is_error.value = false;
    number.value = ''
}
// 处理字符串金额
function convertNegativeToZero(currencyString) {
    // 检查字符串是否包含负号
    if (/-[\d,]+$/.test(currencyString)) {
        return true;
    }
    return false;
}
onMounted(async () => {
    console.log(usestore.userInfo?.user_info?.balance_money,'11111111')
    await store.getviplist()
    
    //是否存在编码
    let curtime = Math.floor(Date.now() / 1000);
    
    if (usestore.encode.expire_time) {
        if (usestore.encode.expire_time > curtime) {
            is_vip.value = true
            is_loding.value = true
        } else {
            handlescale()
            is_loding.value = true
        }
    } else {
        handlescale()
        is_loding.value = true
    }


})
</script>

<style lang="less" scoped>
.total-content {
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15);
    border-radius: 20px;
    border: 1px solid #FFFFFF;
}

.block {
    display: inline-block;
    width: 3.2rem;
    height: 3.2rem;
    color: #fff;
    font-size: 2rem;
    font-weight: 600;
    text-align: center;
    /* background-color: #FFFFFF; */
    background: linear-gradient(180deg, #FFA100 0%, #FF6A00 50%, #FFA100 100%);
    ;
    line-height: 3rem;
    border-radius: 0.5rem;
}

.colon {
    display: inline-block;
    margin: 0 5px;
    color: #FFFFFF;
    font-size: 20px;
    font-weight: 600;
}

.Placements {
    background: linear-gradient(270deg, #1460E3 0%, #003184 100%);
    border-radius: 15px 15px 15px 15px;
}

.showroom {
    background: linear-gradient( 270deg, #00ACF0 0%, #0075DC 100%);
    border-radius: 15px 15px 15px 15px;
}

.jinx {
    background: linear-gradient(-72deg, #FF8400 0%, #FF5900 100%);
    border-radius: 15px 15px 15px 15px;
}

.promotion {
    background: linear-gradient(270deg, #00E362 0%, #009942 100%);
    border-radius: 15px 15px 15px 15px;
}

.jinxabs {
    border-radius: 0px 0px 8px 8px;
}

.scale {
    background: linear-gradient(90deg, rgba(0, 156, 255, 0.5) 0%, rgba(0, 102, 255, 0.4) 21%, rgba(0, 150, 255, 0.2) 52%, rgba(0, 102, 255, 0.4) 81%, rgba(0, 156, 255, 0.5) 100%);
    box-shadow: 0px 1px 2px 0px rgba(15, 9, 13, 0.2), 0px -2px 3px 0px rgba(0, 79, 224, 0.45), 0px 2px 3px 0px rgba(137, 215, 255, 0.45), 0px 0px 15px 0px rgba(8, 118, 255, 0.4);
    border-radius: 23px;
    border: 1px solid #0966FF;
    // border-image: linear-gradient(0deg, #093DFF, #0966FF) 1 1;
}

.line {
    border-right: 1px solid #FF5001;
}

.institution {
    background: linear-gradient(89deg, #FF8800, #FFBB00);
    border-radius: 10px;
}

.institution_btn {
    background: linear-gradient(90deg, #FA6C00FF, #FFA600FF);
    border-radius: 6px;
    border: 1px solid #FFFFFF;
    height: 33px;
}

.institution1 {
    background: linear-gradient(89deg, #01AA0FFF, #22E632FF);
    border-radius: 10px;
}

.institution_btn1 {
    background: linear-gradient(90deg, #00960CFF, #00CC11FF);
    border-radius: 6px;
    border: 1px solid #FFFFFF;
    height: 33px;
}

.institution2 {
    background: linear-gradient(89deg, #006DFFFF, #00D9FFFF);
    border-radius: 10px;
}

.institution_btn2 {
    background: linear-gradient(90deg, #005FE3FF, #00B7FFFF);
    border-radius: 6px;
    border: 1px solid #FFFFFF;
    height: 33px;
}

:deep(.van-popup) {
    overflow-y: inherit;
}

:deep(.van-field__control) {
    color: #000000 !important;
}

:deep(.van-field__control::placeholder) {
    color: #000000 !important;
}

:deep(.van-cell) {
    border-radius: 6px;
    padding: 9px 11px !important;
}

.cancel_btn {
    background: linear-gradient(89deg, #FF6E35 0%, #FFAD72 100%);
    box-shadow: 0px 2px 4px 0px rgba(15, 9, 13, 0.1);
    border-radius: 6px;
}

.gradient-border-box-small {
    /* 容器的定位上下文 */
    position: relative;
    /* 圆角和半透明背景 */
    background-color: rgba(34, 37, 40, 0.7);
    box-shadow: inset 0px 0 7px 0px #47577B;
    /* 白色，60% 透明度 */
    border-radius: 15px;
    /* **重要：保持和伪元素一致的圆角** */
    overflow: hidden;
    /* 确保内容不会溢出圆角 */
    /* Optional: 添加一个毛玻璃效果 (现代浏览器) */
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    /* Safari 兼容 */
}

/* 创建渐变边框的伪元素 */
.gradient-border-box-small::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    /* 渐变背景作为边框 */
    background: linear-gradient(to right, #FF5001, #FFA201, #FF5001);
    /* 你的渐变颜色 */
    border-radius: 15px;
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