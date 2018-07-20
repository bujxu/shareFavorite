<?php

namespace app\api\service;
use \think\Db;

class Order
{
    protected $oProducts;
    protected $products;
    protected $uid;

    public function place($uid, $oProducts)
    {
        $this->oProducts = $oProducts;
        $this->uid = $uid;
        $this->products = $this->getProductsByOrder($oProducts);
        $status = $this->getOrderStatus();
        if (!$status['pass'])
        {
            $status['order_id'] = -1;
            return $status;
        }

        $orderSnap = $this->snapOrder($status);
        $order = $this->createOrder($orderSnap);
        $order['pass'] = true;
        return $order;
    }

    private function createOrder($snap)
    {
        Db::startTrans();
        try
        {
            $orderNo = $this->makeOrderNo();
            $order = new \app\api\model\Order();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);

            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;

            foreach ($this->oProducts as &$p)
            {
                $p['order_id'] = $orderID;
            }
            $orderProduct = new \app\api\model\OrderProduct();
            $orderProduct->saveAll($this->oProducts);
            Db::commit();
            return [
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        }
        catch (Exception $ex)
        {
            Db::rollback();
            throw $ex;
        }
    }

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => [],
            'snapAddress' => null,
            'snapName' => '',
            'snapImg' => '',
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = \json_encode($this->getUserAddress());
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['main_img_url'];

        return $snap;
    }



    private function getUserAddress()
    {
        $userAddress = \app\api\model\UserAddress::where('user_id', '=', $this->uid)->find();
        if (!$userAddress)
        {
            throw new \app\lib\exception\UserException([
                'msg' => '收货地址不存在， 下单失败',
                'errorCode' => 60001
            ]);
        }

        return $userAddress->toArray();
    }

    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,
            'pStatusArray' => [],
            'totalCount' => 0,

        ];

        foreach ($this->oProducts as $oProudct)
        {
            $pStatus = $this->getProductStatus($oProudct['product_id'], $oProudct['count'], $this->products);
            if (!$pStatus['haveStock'])
            {
                $status['pass'] = false;
            }

            $status['orderPrice'] += $pStatus['totalPrice'];
            $status['totalCount'] += $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
        }

        return $status;
    }

    private function getProductStatus($oPID, $oCount, $products)
    {
        $pIndex = -1;
        $pStatus = [
            'id' => null,
            'haveStock' => false,
            'count' => 0,
            'name' => '',
            'totalPrice' => 0
        ];

        for ($i = 0; $i < count($products); $i++)
        {
            if ($oPID == $products[$i]['id'])
            {
                $pIndex = $i;
            }
        }

        if ($pIndex == -1)
        {
            throw new \app\lib\exception\OrderException([
                'msg' => 'id:'.$oPID.'商品不存在， 创建订单失败'
            ]);
        }
        else
        {
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['count'] = $oCount;
            $pStatus['name'] = $product['name'];
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            if ($product['stock'] - $oCount >= 0)
            {
                $pStatus['haveStock'] = true;
            } 

            return $pStatus;
        }
    }
    private function getProductsByOrder($oProducts)
    {
        $oPIDs = [];
        foreach ($oProducts as $item) {
            array_push($oPIDs, $item['product_id']);
        }

        $products = \app\api\model\Product::all($oPIDs)->visible(['id', 'price', 'stock', 'name', 'main_img_url'])->toArray();

        return $products;
    }
}