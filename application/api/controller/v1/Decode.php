<?php

namespace app\api\controller\v1;
use \app\api\service\Decode as DecodeService;
use \app\api\model\User as UserModel;
use \app\api\service\Token as TokenService;

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

    public function getGroups()
    {
        $result = UserModel::getGroupsWithUser(TokenService::getCurrentUid());

        return $result;
    }
}