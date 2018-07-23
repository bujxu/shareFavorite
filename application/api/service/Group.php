<?php

namespace app\api\service;
use \think\Db;
use \app\api\service\Token as TokenService;

class Group
{
    public static function createGroup($openGId)
    {
        Db::startTrans();
        try
        {
            $group = new \app\api\model\Group();
            $group->openGId = $openGId;

            $group->save();

            Db::commit();
            return $group;
        }
        catch (Exception $ex)
        {
            Db::rollback();
            throw $ex;
        }
    }

    public static function createMap($groupId)
    {
        Db::startTrans();
        try
        {
            $userGroup = new \app\api\model\UserGroup();
            $userGroup->group_id = $groupId;
            $userGroup->user_id = TokenService::getCurrentUid();
            $userGroup->save();

            Db::commit();
            return $userGroup;
        }
        catch (Exception $ex)
        {
            Db::rollback();
            throw $ex;
        }
    }
}