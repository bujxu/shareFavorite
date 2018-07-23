<?php
namespace app\api\model;


class Group extends BaseModel
{
    // public function getUrlAttr($value, $data)
    // {
    //     return $this->prefixImgUrl($value, $data);
    // }

    // public static function getGroupsByUserID($userID)
    // {
    //     $products = self::where('category_id', '=', $categoryID)->select();
    //     return $products;
    // }

    public static function getGroupIdByOpenGId($openGId)
    {
        $group = self::where(['openGId' => $openGId])->find();
        return $group;
    }

}