<?php

namespace app\api\controller;

use app\BaseController;
use think\response\Json;

class Banner extends BaseController
{
    public function index(): Json
    {
        $banner = i18n_home_banner();
        return json(msg(0, $banner, ''));
    }

}
