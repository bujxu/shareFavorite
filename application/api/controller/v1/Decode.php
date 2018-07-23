<?php

namespace app\api\controller\v1;
use \app\api\service\Decode as DecodeService;
use \app\api\model\Group as GroupModel;

class Decode
{
    protected $beforeActionList = [
        'checkDecodeData'
    ];


    public function decodeShare($encryptedData='', $iv='')
    {
        $result = DecodeService::getShareData($encryptedData, $iv);

        return $result;
    }

    public function decodeUser($encryptedData='', $iv='')
    {
        $result = DecodeService::getUserData($encryptedData, $iv);

        return $result;
    }
}