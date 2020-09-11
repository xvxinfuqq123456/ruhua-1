<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/26 0026
 * Time: 16:32
 */

namespace app\services;


use app\model\Order as OrderModel;
use think\facade\Log;

class NotifyService
{
    //异步接收微信回调，更新订单状态
    public function NotifyEditOrder($orderNo)
    {
            return $this->upOrder($orderNo);

    }


    //更新订单状态
    private function upOrder($orderNo)
    {

        try {

            //Lock方法是用于数据库的锁机制;
            $order = OrderModel::with(['ordergoods', 'users','invitecode'])->where('order_num', $orderNo)->lock(true)->find();
            if ($order&&$order['payment_state'] == 0 && $order['state'] == 0) {
                OrderModel::where('order_id', $order['order_id'])->update(['payment_state' => 1, 'pay_time' => time()]);//更新订单状态为已支付

                event('ReduceStock', $order);//扣除库存

                event('InvoiceLog', $order);//检查是否需要开发票
                event('SendGzhDeliveryMessage', [$order, 1, '']);//公众号发送模板消息通知管理员

                if ($order['payment_type'] == 'wx') {

                    event('SendGzhDeliveryMessage', [$order, 5, $order['user_id']]);//公众号发送模板消息通知用户

                } else if ($order['payment_type'] == 'xcx') {
                    //小程序发送模板消息
                }
            }
        } catch (\Exception  $ex) {
            Log::error('更新订单Notify:' . $ex->getMessage());
            return false;
        }
        return true;
    }

    //更新订单状态
    private function upPtOrder($orderNo)
    {
        try {
            //Lock方法是用于数据库的锁机制;
            $order = OrderModel::with(['ordergoods', 'users'])->where('order_num', $orderNo)->lock(true)->find();

            if ($order['payment_state'] == 0 && $order['state'] == 0) {
                $order->save(['payment_state' => 1, 'pay_time' => time()]);//更新订单状态为已支付
                if ($order['is_captain'] == 1 ) {
                    $data['state'] = 1;
                }



                event('ReduceStock', $order);//扣除库存
                if ($data['state'] == 2) {



                }
                $res['order_goods'] = $order;
                if ($order['payment_type'] == 'wx') {
                    event('SendGzhDeliveryMessage', [$order, 5, $order['user_id']]);//公众号发送模板消息通知用户
                } else if ($order['payment_type'] == 'xcx') {
                    //小程序发送模板消息
                }

            }
        } catch (\Exception  $ex) {
            Log::error('更新订单Notify:' . $ex->getMessage());
            return false;
        }
        return true;
    }
}