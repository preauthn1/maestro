<?php

namespace app\common\repository;

use app\common\model\ActivitynewRecordModel;

class ActivityNewRecordRepository extends Repository
{
    protected $model;

    public function __construct()
    {
        //绑定模型
        $this->model = new ActivitynewRecordModel();
    }

    // 获取用户活动记录
    public function getUserRecords($user_id, $activity_id, $recharge_bonus_id = null, $status = -1)
    {
        $where = [
            ['user_id', '=', $user_id],
            ['activity_id', '=', $activity_id],
        ];
        if (in_array($status, [0, 1])) {
            $where[] = ['status', '=', $status];
        }
        if ($recharge_bonus_id) {
            $where[] = ['recharge_bonus_id', '=', $recharge_bonus_id];
            return self::findWhere($where);
        }
        return self::order(['id' => 'desc'])->selectWhere($where);
    }
}
