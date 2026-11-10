import http from "../utils/request";
//successTips需要提示成功时使用
export class TeamService {
    static async teamInfo(params) {
        try {
            return await http.get(
                "api.php/team/team_info", { params }
            );
        } catch (error) {
            throw error;
        }
    }
    static async teamDetail(params) {
        try {
            return await http.get(
                "api.php/team/detail/1",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }

    static async directPushTask(params) {
        try {
            return await http.get(
                "api.php/task/rec_reward",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //领取任务奖励
    static async get_rec_reward(params) {
        try {
            return await http.get(
                "api.php/task/get_rec_reward",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //邀请活动奖励
    static async get_event_rewards(params) {
        try {
            return await http.get(
                "/api.php/activitynew",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //领取邀请活动奖励
    static async getReward(params) {
        try {
            return await http.get(
                "/api.php/activitynew/getReward",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    static async getActiveReward(params) {
        try {
            return await http.get(
                "/team_active_reward/index",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    static async teamActiveReward(params) {
        try {
            return await http.get(
                "/api.php/team_active_reward/getReward",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //连续推广签到
    static async promotion_reward_list(params) {
        try {
            return await http.get(
                "/api.php/user/promotion_reward_list",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //领取连续推广奖励
    static async get_promotion_reward(params) {
        try {
            return await http.get(
                "/api.php/user/receive_promotion_reward",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //参与活动
    static async join_activity(params) {
        try {
            return await http.get(
                "/api.php/activitynew/join",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //领取活动奖励
    static async get_activity_reward(params) {
        try {
            return await http.get(
                "/api.php/activitynew/getReward",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //活动详情
    static async activity_detail(params) {
        try {
            return await http.get(
                "/api.php/activitynew/recharge_bonus",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
    //立即充值
    static async recharge_now(params) {
        try {
            return await http.get(
                "/api.php/activitynew/recharge_now",
                { params }
            );
        } catch (error) {
            throw error;
        }
    }
}