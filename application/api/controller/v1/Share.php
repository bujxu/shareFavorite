<?php

namespace app\api\controller\v1;
use \app\api\service\Share as ShareService;
use \app\api\model\Group as GroupModel;

class Share 
{
    public function getOpenGId($encryptedData='', $iv='')
    {
        (new \app\api\validate\GetOpenGId)->goCheck();
        $result = ShareService::get($encryptedData, $iv);

        return json_decode($result);
    }
}