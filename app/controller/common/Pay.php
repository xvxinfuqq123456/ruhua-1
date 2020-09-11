<?php

namespace app\controller\common;


use app\services\AppPayService;
use app\services\GzhNotifyService;
use app\services\GzhVipNoticefyService;
use app\model\SysConfig;
use app\services\NotifyService;
use app\services\PayService;
use app\services\WxNotifyService;
use app\services\TokenService;
use app\validate\IDPostiveInt;
use bases\BaseController;
use app\model\Order as OrderModel;
use GzhPay\JsApi;
use GzhPay\WxPayConfig;

class Pay extends BaseController
{
	 private $orderID;
    //公众号-我的订单页面中进行支付
    public function gzhPaySecond($id)
    {
       (new IDPostiveInt())->goCheck();

        $order=OrderModel::where('order_id',$id)->find();

        $order_data['order_num']=$order['order_num'];
        $order_data['pay_money']=$order['pay_money'];
        $openid=TokenService::getCurrentTokenVar('openid');
        $gzh['web_name']=SysConfig::where(['key'=>'web_name'])->value('value');
        $gzh['api_url']=SysConfig::where(['key'=>'api_url'])->value('value');
        $res=(new JsApi())->gzh_pay($openid,$order_data,$gzh);
        return $res;
    }
    //公众号回调
    public function gzh_back()
    {
        $config = new WxPayConfig();
        $notify = new GzhNotifyService();
        $notify->Handle($config, false);
    }

    //小程序创建订单
    public function getPreOrder($id = '')
    {
        (new IDPostiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }
    //小程序支付回调:订单
    public function receiveNotify()
    {
        $notify = new WxNotifyService();
        $notify->Handle();
    }




    //app支付
    public function getAppPayData($id = ''){
        (new IDPostiveInt())->goCheck();
        $pay = new AppPayService($id);
        return $pay->pay();
    }
    //app支付回调
    public function appNotify()
    {
        $notify = new WxNotifyService();
        $notify->Handle();
        exit;
        /*$order_num = input();
        $notify = new NotifyService();
        Log::error("app_pay_back".json_encode($order_num,JSON_UNESCAPED_UNICODE));
        exit();
        $res = $notify->NotifyEditOrder($order_num);
        return app('json')->success($res);*/
    }





}