<?php

namespace app\api\controller;

use app\api\transform\ActivityNewRechargeBonusTransform;
use app\api\transform\ActivitynewTransform;
use app\common\model\ActivitynewModel;
use app\common\model\ActivitynewPasswordRecordModel;
use app\common\model\ActivitynewRecordModel;
use app\common\model\MoneyLogModel;
use app\common\repository\ActivityNewPasswordRepository;
use app\common\repository\ActivityNewRechargeBonusRepository;
use app\common\repository\ActivityNewRecordRepository;
use app\common\repository\ActivityNewRepository;
use think\response\Json;
use util\Locker;

class Activitynew extends Base
{
    public function index(): Json
    {
        $page = $this->request->param('page/d', 1);
        $pageSize = $this->request->param('pageSize/d', 10);
        $type = $this->request->param('type/d', 0);
        $user_id = $this->userid;
        $list = ActivitynewModel::where('status', 1)
            ->where('type', $type)
            ->order('id ASC')
            ->paginate(['page' => $page, 'list_rows' => $pageSize]);
        // 获取用户一级下级人数
        foreach ($list as $k => $v) {
            if ($v->type == 7) {
                // 已领取奖励记录数
                $receive_num = ActivitynewRecordModel::where('activity_id', $v['id'])
                    ->where('user_id', $user_id)
                    ->where('status', 1)
                    ->count();
                $un_receive_num = ActivitynewRecordModel::where('activity_id', $v['id'])
                    ->where('user_id', $user_id)
                    ->where('status', 0)
                    ->count();
                $v['records_count'] = $un_receive_num;
                // 判断是否有未领取奖励的记录
                $can_receive = ActivitynewRecordModel::where('activity_id', $v['id'])
                    ->where('user_id', $user_id)
                    ->where('status', 0)
                    ->count();
                $v['can_receive'] = $can_receive;
                //            $team_count = UserModel::whereTime('create_time', 'between',
                //                [strtotime($v['start_time']), strtotime($v['end_time'])])
                //                ->where('leader1', $user_id)
                //                ->count();
                //            $team_count = $team_count - $receive_num;
                //            $v['user_count'] = $team_count;
                $v['user_count'] = $un_receive_num;
                //            $v['ratio'] = sprintf4f($un_receive_num / $team_count * 100, 2, true);
                $v['ratio'] = $un_receive_num ? 100 : 0;
            } else {
                if ($v->type == 8) {
                    // 用户参与状态
                    $v['joined'] = (int)(new ActivitynewPasswordRepository)->checkPasswordStatus($user_id, $v['id']);
                }
            }
        }
        $data = (new ActivitynewTransform)->transformPages($list->toArray());
        return json(msg(0, $data, ''));
    }

    /**
     * 领取奖励
     */
    public function getReward(): Json
    {
        $user_id = $this->userid;
        (new Locker('get_reward_lock_'.$user_id))->lock();

        $id = $this->request->param('id');
        $recharge_bonus_id = $this->request->param('recharge_bonus_id');
        // 获取任务信息
        $activity = ActivitynewModel::find($id);
        if (!$activity) {
            return json(msg(-1, '', lang('活动不存在')));
        }
        if ($activity['status'] != 1) {
            return json(msg(-1, '', lang('活动已停止')));
        }

        $where = [
            'user_id' => $user_id,
            'status' => 0,
            ['reward', '>', 0]
        ];
        if ($recharge_bonus_id) {
            $where[] = ['recharge_bonus_id', '=', $recharge_bonus_id];
        }
        $rewards = (new ActivityNewRecordRepository())->selectWhere($where);

        if ($rewards->isEmpty()) {
            return json(msg(-1, '', lang('暂无奖励可领取')));
        }
        $remark = '活动奖励';
        $detail_type = 8;
        switch ($activity->type) {
            case 7:
                $remark = '分享奖励';
                break;
            case 8:
                $remark = '充值送金';
                $detail_type = 26;
                break;
        }
        $money_log_model = new MoneyLogModel();
        foreach ($rewards as $item) {
            $money = $item['reward'];
            $money_log_model->changeMoney($user_id, $money, MoneyLogModel::TYPE_USER, 'add', $remark,
                $remark.'_'.$money, $detail_type, 0, $item->from_user_id, $item['id']);
            $item->status = 1;
            $item->save();
        }

        if ($activity->type == 8) {
            // 创建一条未领取的记录
            $record = new ActivitynewRecordModel();
            $record->activity_id = $id;
            $record->recharge_bonus_id = $rewards[0]['recharge_bonus_id'];
            $record->user_id = $user_id;
            $record->from_user_id = $user_id;
            $record->status = 0; // 未领取奖励
            $record->password = $rewards[0]['password'];
            $record->save();
        }
        return json(msg(0, '', lang('领取成功')));
    }

    // 参加活动
    public function join(): Json
    {
        $user_id = $this->userid;
        (new Locker('join_activity_lock_'.$user_id))->lock();
        $param = $this->request->param();
        $param['user_id'] = $user_id;
        $activity_id = input('activity_id', '');
        $password = input('password', '');
        // 增加验证器
        $this->validate($param, 'JoinActivitynewValidate');

        // 检查用户是否已参加
        $record = ActivitynewPasswordRecordModel::where('activity_id', $activity_id)
            ->where('user_id', $user_id)
            ->find();

        $activity_password = (new ActivitynewPasswordRepository)->getPasswordByActivityId($activity_id, $password);
        if ($record && $activity_password && $record->password == $activity_password->password) {
            return json(msg(0, '', lang('您已参加该活动')));
        }

        if ($password != $activity_password->password) {
            return json(msg(-1, '', lang('活动解锁密码不正确')));
        }

        if ($record) {
            $record->password = $password;
            $record->save();
            return json(msg(0, '', lang('参加活动成功')));
        }

        // 创建记录
        $record = new ActivitynewPasswordRecordModel();
        $record->is_xuni = $this->userInfo['is_xuni'];
        $record->activity_id = $activity_id;
        $record->user_id = $user_id;
        $record->password = $password;
        $record->save();

        return json(msg(0, '', lang('参加活动成功')));
    }

    // 充值送金活动列表
    public function recharge_bonus(): Json
    {
        $activity_id = input('activity_id', '');
        $user_id = $this->userid;
        $activity = (new ActivityNewRepository())->getActivityById($activity_id);
        if (!$activity || $activity->type != 8) {
            return json(msg(0, [], ''));
        }
        $recharge_bonuses = (new ActivityNewRechargeBonusRepository)->getRechargeBonusByActivityId($activity_id, 1);
        $data = (new ActivityNewRechargeBonusTransform())->transformCollection($recharge_bonuses);
        // 获取用户充值记录
        $records = (new ActivityNewRecordRepository())->getUserRecords($user_id, $activity->id);
        foreach ($data as &$item) {
            $item['end_time'] = i18n_datetime2str($activity->end_time);
            $item['recharge_amount'] = i18n_currency(0);
            $item['reward'] = i18n_currency(0);
            $item['reward_value'] = 0;
            if ($records) {
                $record = collect($records)
                    ->where('user_id', $user_id)
                    ->where('activity_id', $activity_id)
                    ->where('recharge_bonus_id', $item['id'])
                    ->first();
                if ($record) {
                    $item['recharge_amount'] = i18n_currency($record['recharge_amount']);
                    $item['reward'] = i18n_currency($record['reward']);
                    $item['reward_value'] = $record['reward'];
                }
            }
        }
        return json(msg(0, $data, ''));
    }

    // 充值送金活动-立即充值
    public function recharge_now(): Json
    {
        $activity_id = input('activity_id', 0);
        $recharge_bonus_id = input('recharge_bonus_id', 0);
        $user_id = $this->userid;

        // 判断活动和充值规则是否存在
        $activity = (new ActivityNewRepository())->getActivityById($activity_id);
        if (!$activity) {
            return json(msg(0, msg('-1', '', lang('活动不存在')), lang('活动不存在')));
        }
        if ($activity->start_time > time()) {
            return json(msg('-1', '', lang('活动未开始')));
        }
        if ($activity->end_time < time()) {
            return json(msg('-1', '', lang('活动已停止')));
        }
        if ($activity->status != 1) {
            return json(msg('-1', '', lang('活动已停止')));
        }
        $recharge_bonus = (new ActivityNewRechargeBonusRepository())->getRechargeBonusByActivityId($activity_id, 1);
        if ($recharge_bonus->isEmpty()) {
            return json(msg(0, msg('-1', '', lang('活动不存在')), lang('活动不存在')));
        }

        // 检验密码是否过期
        $join_status = (new ActivitynewPasswordRepository)->checkPasswordStatus($user_id, $activity_id);
        if (!$join_status) {
            return json(msg(0, msg('-1', '', lang('请重新输入活动解锁密码')), lang('请重新输入活动解锁密码')));
        }

        // 检查用户是否已参加
        $record = ActivitynewRecordModel::where('activity_id',$activity_id)
            ->where('recharge_bonus_id', $recharge_bonus_id)
            ->where('user_id', $user_id)
            ->where('status', 0)
            ->find();
        if ($record) {
            return json(msg(0, '', lang('success')));
        }

        // 获取已参加活动的人数
        $joined_user_count = (new ActivityNewRecordRepository)->model()
            ->where('user_id', '<>', $user_id)
            ->where('recharge_bonus_id', $recharge_bonus_id)
            ->where('activity_id', $activity_id)
            ->group('user_id')
            ->count();
        // 获取活动金额门槛
        $recharge_bonus = (new ActivityNewRechargeBonusRepository())->find($recharge_bonus_id);
        if ($joined_user_count >= $recharge_bonus->num) {
            return json(msg('-1', '', lang('活动名额已满，无法参与')));
        }


        // 创建记录
        $record = new ActivitynewRecordModel();
        $record->activity_id = $activity_id;
        $record->recharge_bonus_id = $recharge_bonus_id;
        $record->user_id = $user_id;
        $record->from_user_id = $user_id;
        $record->status = 0; // 未领取奖励
        $record->save();

        return json(msg(0, '', lang('success')));
    }
}
