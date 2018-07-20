<?php

namespace app\api\controller\v1;
use app\api\service\Token as TokenService;
use app\api\model\UserAddress;
use app\api\model\User as UserModel;

class Address extends \app\api\controller\BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress']
    ];


    public function createOrUpdateAddress()
    {
        $validate = new \app\api\validate\AddressNew;
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user)
        {
            throw new \app\lib\exception\UserException;
        }

        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;

        if (!$userAddress)
        {
            $user->address()->save($dataArray);
        }
        else
        {
            $user->address->save($dataArray);
        }


        return json(new \app\lib\exception\SuccessMessage(), 201);
    }
}