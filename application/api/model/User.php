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

    public function UserGroup()
    {
        return $this->hasMany('UserGroup', 'user_id', 'id');
    }

    public function group()
    {
        return $this->belongsToMany('Group', 'group_id', 'user_id');
    }

    public function groups()
    {
        return $this->belongsTo('Group', 'group_id', 'id');
    }
    
    public static function getGroupsWithUser($id)
    {
        $groups = self::where(['id' => $id])->with(['UserGroup', 'UserGroup.groups'])->select();
        return $groups;
    }

}