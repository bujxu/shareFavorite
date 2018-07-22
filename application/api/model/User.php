<?php 

namespace app\api\model;

class User extends BaseModel
{
    public function address()
    {
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    public static function getByOpenID($openid)
    {
        $user = self::where('openid', '=', $openid)->find();

        return $user;
    }

    public function groups()
    {
        return $this->belongsToMany('Group', 'user_group', 'group_id', 'user_id');
    }
}