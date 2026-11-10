<?php

namespace app\command;

use app\admin\model\LevelModel;
use app\admin\model\MemberModel;
use app\admin\model\RechargeModel;
use app\api\transform\PromotionRewardDayTransform;
use app\common\model\ActivitynewModel;
use app\common\model\ConfigModel;
use app\common\model\HomeUserBalanceModel;
use app\common\model\HomeUserVirtualModel;
use app\common\model\MoneyLogModel;
use app\common\model\ProductInfoModel;
use app\common\model\RecommendModel;
use app\common\model\SignModel;
use app\common\model\TaskQmtaiModel;
use app\common\model\TaskSummaryModel;
use app\common\model\TaskSummaryV2Model;
use app\common\model\TeamActiveRewardV2Model;
use app\common\model\UserModel;
use app\common\repository\FinanceProductOrderRepository;
use app\common\repository\HomeUserBrokeragePoolRepository;
use app\common\repository\HomeUserExcessAdRatioConfigRepository;
use app\common\repository\HomeUserRepository;
use app\common\repository\MemberWaterRepository;
use app\common\repository\PromotionRewardDayRepository;
use app\common\repository\PromotionRewardRecordRepository;
use app\common\repository\PromotionRewardRepository;
use app\common\repository\TaskSessionRepository;
use app\common\repository\TaskSummaryRepository;
use app\services\KwaiService;
use Carbon\Carbon;
use email\MailgunEmail;
use email\Smtpserver;
use think\facade\Cache;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\Model;
use think\response\Json;
use util\Locker;
use util\RedisSyncPool;

class Test extends Command
{

    protected function configure()
    {
        $this->setName('test')->setDescription('测试');
    }

    private $level = [];

    public function execute(Input $input, Output $output)
    {
        // 清除指定的缓存
//        $clear = Cache::clear('index_video_list');
//        var_dump($clear);
        $user_id = 7;
//        $date = '2025-11-04';
//        $sum_task = TaskSummaryV2Model::field('user_id,  sum(task_level1 + task_level2 ) sum_task') //得到活跃用户数据
//        ->where('user_id', $user_id)
//            ->where("date", $date)
//            ->group('user_id')
//            ->find();
//        var_dump($sum_task);
        $orderWhere[]  = ['status', 'in', [FinanceProductOrderRepository::STATUS_PURCHASED, FinanceProductOrderRepository::STATUS_SETTLED]];
        if (true) {
            $orderWhere[] = ['create_time', 'between', [strtotime("2025-11-04"), strtotime("2025-11-04 23:59:59")]];
            // 当天
        }
        // 获取一级下级首次充值达到一定数额并且已经购买广告位的用户数
        $query =  UserModel::hasWhere('financeProductOrder', $orderWhere
        )->hasWhere("recharge", [['money', '>=', 500],['is_first', "=", 1]])
            ->where(['leader1' => $user_id])->where("create_time", ">", strtotime(config('app.activity_start_time')));
        $count = $query->count();
        var_dump($count);
    }

}
