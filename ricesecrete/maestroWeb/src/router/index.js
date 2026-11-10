// src/router/index.ts

import { createRouter, createWebHashHistory } from 'vue-router'
import { useUserStore } from '../store/userinfo';
import { KeepAlive } from 'vue';
const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('../view/Home.vue'),
        meta: {
            showTabbar: false //控制是否显示底部导航
        }
    },
    {
        path: '/upgrade',
        name: 'Upgrade',
        component: () => import('../view/Upgrade.vue'),
        meta: {
            showTabbar: false //控制是否显示底部导航
        }
    },

    {
        path: '/invitation',
        name: 'Invitation',
        component: () => import('../view/Invitation.vue'),
        meta: {
            showTabbar: false //控制是否显示底部导航
        }
    },
    {
        path: '/profile',
        name: 'Profile',
        component: () => import('../view/Profile.vue'),
        meta: {
            showTabbar: false //控制是否显示底部导航
        }
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../view/newLogin.vue')
      
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('../view/newRegister.vue')
      
    },
    {
        path: '/notice',
        name: 'Notice',
        component: () => import('../view/Notice.vue'),
        meta:{KeepAlive:true}
      
    },
    {
        path: '/noticeDetails',
        name: 'NoticeDetails',
        component: () => import('../view/NoticeDetails.vue')
      
    },
    {
        path: '/helpCenter',
        name: 'HelpCenter',
        component: () => import('../view/HelpCenter.vue')
      
    },
    {
        path: '/historyRecord',
        name: 'HistoryRecord',
        component: () => import('../view/HistoryRecord.vue')
      
    },
    {
        path: '/securePassword',
        name: 'SecurePassword',
        component: () => import('../view/SecurePassword.vue')
      
    },
    {
        path: '/loginPassword',
        name: 'LoginPassword',
        component: () => import('../view/LoginPassword.vue')
      
    },
    {
        path: '/movieDetails',
        name: 'MovieDetails',
        component: () => import('../view/MovieDetails.vue')
    },
    {
        path: '/movieLibrary',
        name: 'MovieLibrary',
        component: () => import('../view/MovieLibrary.vue')
    },
    {
        path: '/financialcenter',
        name: 'FinancialCenter',
        component: () => import('../view/FinancialCenter.vue')
    },
    {
        path: '/investmentrecords',
        name: 'InvestmentRecords',
        component: () => import('../view/InvestmentRecords.vue')
    },
    {
        path: '/rechargechannels',
        name: 'RechargeChannels',
        component: () => import('../view/RechargeChannels.vue')
    },
    {
        path: '/rechargedetail',
        name: 'RechargeDetail',
        component: () => import('../view/RechargeDetail.vue')
    },
    {
        path: '/eWallet',
        name: 'EWallet',
        component: () => import('../view/EWallet.vue')
    },
    {
        path: '/withdrawal',
        name: 'Withdrawal',
        component: () => import('../view/Withdrawal.vue')
    },
    {
        path: '/choosetype',
        name: 'ChooseType',
        component: () => import('../view/ChooseType.vue')
    },
    {
        path: '/upgradelist',
        name: 'UpgradeList',
        component: () => import('../view/UpgradeList.vue')
    },
    {
        path: '/commadvertis',
        name: 'CommAdvertis',
        component: () => import('../view/CommAdvertis.vue')
    },
    {
        path: '/yuebao',
        name: 'Yuebao',
        component: () => import('../view/Yuebao.vue')
    },
    {
        path: '/profitrules',
        name: 'ProfitRules',
        component: () => import('../view/ProfitRules.vue')
    },
    {
        path: '/membershiplist/:id',
        name: 'MembershipList',
        component: () => import('../view/MembershipList.vue')
    },
    {
        path: '/dailyattendance',
        name: 'DailyAttendance',
        component: () => import('../view/DailyAttendance.vue')
    },
    {
        path: '/bankcard',
        name: 'BankCard',
        component: () => import('../view/BankCard.vue')
    },
    {
        path: '/editbankcard',
        name: 'EditBankCard',
        component: () => import('../view/EditBankCard.vue')
    },
    {
        path: '/directpushtask',
        name: 'DirectPushTask',
        component: () => import('../view/DirectPushTask.vue')
    },
    {
        path: '/agreement',
        name: 'Agreement',
        component: () => import('../view/Agreement.vue')
    },
    {
        path: '/helpdetail',
        name: 'HelpDetail',
        component: () => import('../view/HelpDetail.vue')
    },
    {
        path: '/newhonouredGuestentory',
        name: 'NewhonouredGuest',
        component: () => import('../view/NewhonouredGuest.vue'),
        meta: {
            showTabbar: false //控制是否显示底部导航
        }
    },
    {
        path: '/turntable',
        name: 'Turntable',
        component: () => import('../view/Turntable.vue'),
    },
    {
        path: '/escrowrecords',
        name: 'EscrowRecords',
        component: () => import('../view/EscrowRecords.vue'),
    },
    {
        path: '/about',
        name: 'About',
        component: () => import('../view/About.vue'),
    },
    {
        path: '/activity',
        name: 'Activity',
        component: () => import('../view/Activity.vue'),
    },
    {
        path: '/activitydetails',
        name: 'Activitydetails',
        component: () => import('../view/ActivityDetails.vue'),
    },
    
    
];
const whiteList = ["/login","/register","/choosetype","/agreement",]

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

//全局前置守卫
router.beforeEach((to,from,next)=>{
    const store = useUserStore();
    if(!whiteList.includes(to.path)){
        if(store.is_login){
            next()
        }else{
            
            store.backRoute = to.fullPath
            router.push("/choosetype")
        }
    }else{
        next();
    }
    
})
export default router;