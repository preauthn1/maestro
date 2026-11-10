<?php

namespace app\common\model;

use app\admin\model\ConfigModel;
use think\console\output\Ask;
use think\facade\Cache;
use think\facade\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use think\Model;
use think\model\relation\HasOne;

class TeamActiveRewardV2Model extends Model
{

    protected $autoWriteTimestamp = true;
    // 确定链接表名
    protected $name = 'team_active_reward_v2';

    const TRIGGER_LEVEL1 = 1; // 命中直推
    const TRIGGER_ACTIVE = 2;  // 命中活跃
    const TRIGGER_LEVEL1_AND_ACTIVE = 3; // 停用

    /**
     * @param int $type
     * @return array|mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * 获取配置
     */
    public function getConfig($type = 1) {
        $team_reward_config = ConfigModel::getConfigValue('team_reward_config_'.$type);
        return $team_reward_config ? json_decode($team_reward_config, true) : [];
    }

    /**
     * 计算用户的团队活跃奖励
     */
    public function calculateReward($task, $type = 1 ) {
        try {
            $config = $this->getConfig($type);
        } catch (DataNotFoundException $e) {
            return false;
        }
        $type1Count = $this->where("user_id", $task['user_id'])
            ->where("status", 1)
            ->where("reward_level", 1)
            ->count();
        if ($type == 2 && count($config) > $type1Count) {
            return false;
        }
        if ($type == 1) {
            // 得到最新的一条初始团队数据
            $lastTeamActiveReward = $this->where("user_id = {$task['user_id']} and reward_level = {$type} ")
                ->order("id desc")
                ->find();
            // 得到本次轮次
            $phase = $lastTeamActiveReward ? $lastTeamActiveReward['phase'] + 1 : 1;
            //得到奖励金额
            $reward_amount = $lastTeamActiveReward ? $lastTeamActiveReward['reward_amount'] + $config['reward_amount_step']: $config['reward_amount'];
            // 如果已经达到最大阶段
            if ($phase > $config['phase_count']) {
                return false;// 已经达成所有成就
            }
            $directUserNum = UserModel::getLevel1ActiveUserNumForRecharge($task['user_id'], $config['level1_recharge_money']);// 得到直推用户总数
            $activeUserNum = $task['sum_task'] - UserModel::getLevel1ActiveUserNumForRecharge($task['user_id'], $config['level1_recharge_money'],true);// 得到活跃用户 1、2级的活跃用户减去当日（一级）的直推用户

            $save_data = [
                'user_id' => $task['user_id'],
                'level1_user' => $directUserNum,//直推用户数
                'sum_active' => $activeUserNum,//总活跃人数
                'reward_level' => $type,//奖励等级
                'phase'=> $phase,//阶段
                'config' => json_encode($config),//配置
                'reward_amount' => $reward_amount,//奖励金额
                'status' => 0,//领取状态
                'trigger_level1_num' => $config['base_level1_user'],// 条件对应的直推人数（计算配置人数）
                'trigger_active_num' => $config['base_active_user'],// 命中对应的活跃人数（计算配置人数）
            ];

            // 如果有一条记录
            $is_save = false;
            if (empty($lastTeamActiveReward)) {
//                $save_data['trigger_type'] = self::TRIGGER_LEVEL1; //设置命中条件
                if ($directUserNum >= $config['base_level1_user']) {
                    $save_data['trigger_type'] = self::TRIGGER_LEVEL1; //设置命中条件设置为命中直推人数
                    $save_data['trigger_level1_num'] = $config['base_level1_user'] + $config['level1_user_up_step'];//直推条件递增
                    $is_save = true;
                } elseif ($activeUserNum >= $config['base_active_user']) {
                    $save_data['trigger_type'] = self::TRIGGER_ACTIVE; //设置命中条件设置为命中活跃人数
                    $save_data['trigger_active_num'] = $config['base_active_user'] + $config['active_user_up_step'];//直推条件递增
                    $is_save = true;
                }
            } else {
                //如果上一次命中是命中的直推人数，那么本轮就需要以上一轮的直推人数作为基础数据
                if ($lastTeamActiveReward['trigger_type'] == self::TRIGGER_LEVEL1) {
                    if ($directUserNum >= $lastTeamActiveReward['trigger_level1_num']) {
                        $save_data['trigger_type'] = self::TRIGGER_LEVEL1; //设置命中条件设置为命中直推人数
                        $save_data['trigger_level1_num'] = $lastTeamActiveReward['trigger_level1_num'] + $config['level1_user_up_step'];//直推条件递增
                        $save_data['trigger_active_num'] = $lastTeamActiveReward['trigger_active_num'];//活跃条件不变
                        $is_save = true;
                    }elseif ($activeUserNum >= $lastTeamActiveReward['trigger_active_num']) {
                        $save_data['trigger_type'] = self::TRIGGER_ACTIVE; //设置命中条件设置为命中活跃人数
                        $save_data['trigger_active_num'] = $lastTeamActiveReward['trigger_active_num'] + $config['active_user_up_step'];//活跃条件递增
                        $save_data['trigger_level1_num'] = $lastTeamActiveReward['trigger_level1_num'];//活跃条件不变
                        $is_save = true;
                    }
                }
                //如果上一次命中是命中的活跃人数，那么本轮就需要以上一轮的活跃人数作为基础数据
                if($lastTeamActiveReward['trigger_type'] == self::TRIGGER_ACTIVE) {
                    if ($directUserNum >= $lastTeamActiveReward['trigger_level1_num']) {
                        $save_data['trigger_type'] = self::TRIGGER_LEVEL1; //设置命中条件设置为命中直推人数
                        $save_data['trigger_level1_num'] = $lastTeamActiveReward['trigger_level1_num'] + $config['level1_user_up_step'];//直推条件递增
                        $save_data['trigger_active_num'] = $lastTeamActiveReward['trigger_active_num'];//活跃条件不变
                        $is_save = true;
                    }elseif ($activeUserNum >= $lastTeamActiveReward['trigger_active_num']) {
                        $save_data['trigger_type'] = self::TRIGGER_ACTIVE; //设置命中条件设置为命中活跃人数
                        $save_data['trigger_active_num'] = $lastTeamActiveReward['trigger_active_num'] + $config['active_user_up_step'];//活跃条件递增
                        $save_data['trigger_level1_num'] = $lastTeamActiveReward['trigger_level1_num'];//活跃条件不变
                        $is_save = true;
                    }
                }
            }
            if ($is_save) {
                $this->save($save_data);
            }
            return true;
        }


        if ($type == 2) {
            foreach ($config as $item => $value) {
                $model = new TeamActiveRewardV2Model();
                // 如果已经存在记录，跳过
                if ($this->where("user_id = {$task['user_id']} and reward_level = {$type} and phase = {$value['phase']}")->find() ){
                    continue;
                }
                // 按阶段来处理
                $directUserNum = UserModel::getLevel1ActiveUserNumForRecharge($task['user_id'], $value['recharge_money']);// 得到直推用户总数
                $activeUserNum = $task['sum_task'] - UserModel::getLevel1ActiveUserNumForRecharge($task['user_id'], $value['recharge_money'],true);// 得到活跃用户 1、2级的活跃用户减去当日的直推用户
                //1、在此页面配置初始团队活跃奖和成长团队活跃奖的条件和奖励金额，两个奖的条件均为直推人数和活跃人数,
                //直推人数定义为：若用户完成注册+首次充值达到指定金额(指定金额在上图中的直推用户首充金额的输入框内配置)+购买任意广告位这三个动作(这三个动作不限在同一天完成)，则该用户算作为直推用户，其中动作任意一个没有完成则不算；
                //活跃人数定义为：若用户当天购买任意广告位,则该用户在当天算作为活跃人数（限第一、二级用户，第三级下级不算做活跃人数）；
                $save_data = [
                    'user_id' => $task['user_id'],
                    'level1_user' => $directUserNum,//直推用户数
                    'sum_active' => $activeUserNum,//总活跃人数
                    'reward_level' => $type,//奖励等级
                    'phase'=> $value['phase'],//阶段
                    'config' => json_encode($value),//配置
                    'reward_amount' => $value['amount'],//奖励金额
                    'status' => 0,//领取状态
                    'trigger_type' =>self::TRIGGER_LEVEL1_AND_ACTIVE,// 命中条件
                    'trigger_level1_num' => $value['direct_referral_num'],// 条件对应的直推人数（计算配置人数）
                    'trigger_active_num' => $value['active_num'],// 命中对应的活跃人数（计算配置人数）
                ];
                if ($type == 2 && ($directUserNum >= $value['direct_referral_num'] && $activeUserNum >= $value['active_num'])) { //初始团队活跃
                    $model->save($save_data); //成长团队活跃
                }
            }
        }

        return true;
    }


    // 统计活奖励统计
    public function summaryActive($output)
    {
        $date = date('Ymd');
        $output->writeln("新版本统计团队活跃数据,日期：{$date}\n");

        $user_task = TaskSummaryV2Model::field('user_id,  sum(task_level1 + task_level2 ) sum_task')
            ->where('date', $date)
            ->group('user_id')
            ->select();
        foreach ($user_task as $task) {
            // 初始团队
            $this->calculateReward($task);
            // 成长团队
            $this->calculateReward($task, 2);
        }
    }

    public function summaryActiveNow($user_id)
    {
        $date = date('Ymd');
        $user_task = TaskSummaryV2Model::field('user_id,  sum(task_level1 + task_level2 ) sum_task')
            ->where('date', $date)
            ->where('user_id', $user_id)
            ->find();
        if ($user_task) {
            // 初始团队
            $this->calculateReward($user_task);
            // 成长团队
            $this->calculateReward($user_task, 2);
        }
    }

    public function user(): HasOne
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id')->field("user_name,leader1,id,tree_id");
    }
}
