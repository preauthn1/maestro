<?php

namespace app\command;

use app\common\model\TaskQmtaiModel;
use think\console\Command;
use think\console\Input;
use think\console\Output;

//完成量化任务 1分钟执行一次
//php think end_qmtai
class EndQmtai extends Command
{

    protected function configure()
    {
        $this->setName('end_qmtai')->setDescription('量化定时任务完成');
    }

    protected function execute(Input $input, Output $output)
    {
        for ($i = 1; $i <= 50; $i++) {
            $task_qmtai_model = new TaskQmtaiModel();
            $task_qmtai_model->finishTask('');
            sleep(1);
            echo date("Y-m-d H:i:s") . " 第{$i}次执行完成\n";
        }
    }
}
