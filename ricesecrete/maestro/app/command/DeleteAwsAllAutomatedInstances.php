<?php

namespace app\command;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class DeleteAwsAllAutomatedInstances extends Command
{

    protected function configure()
    {
        $this->setName('delete_aws_all_automated_instances')->setDescription('测试');
        $this->addOption('num', '', Option::VALUE_OPTIONAL, '实例数量');
    }


    protected function execute(Input $input, Output $output)
    {
        $client = new Client();
        $request = new Request('POST', 'http://38.181.219.8:5000/api/delete-all-automated-instances');
        $res = $client->sendAsync($request)->wait();
        echo $res->getBody();
    }
}
