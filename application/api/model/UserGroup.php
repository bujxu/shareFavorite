<?php

namespace app\api\model;

class UserGroup extends BaseModel
{
    public static function getUserIdByGroupId($GroupId)
    {
        $user = self::where(['group_id' => $GroupId])->find();
        return $user;
    }

    public static function getGroupsByUserId($uid)
    {
        $groups = self::where(['user_id' => $uid])->select();
        return $groups;
    }

    public function groups()
    {
        return $this->belongsTo('Group', 'group_id', 'id');
    }
}   