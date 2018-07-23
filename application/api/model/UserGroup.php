<?php

namespace app\api\model;

class UserGroup extends BaseModel
{
    public static function getUserIdByGroupId($GroupId)
    {
        $user = self::where(['group_id' => $GroupId])->find();
        return $user;
    }
}   