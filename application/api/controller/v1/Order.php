<?php

namespace app\api\controller\v1;
use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
class Order extends \app\api\controller\BaseController
{

    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder']
    ];


    public function placeOrder()
    {
        (new \app\api\validate\OrderPlace)->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        
        $order = new OrderService();
        $status = $order->place($uid, $products);
        
        return $status;
    }
}