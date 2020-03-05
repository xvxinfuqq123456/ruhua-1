<?php
/**ssss
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/10 0010
 * Time: 16:51
 */

namespace app\services;


use app\model\FxBind as FxBindModel;
use app\model\FxRecord as FxRecordModel;
use app\model\Order as OrderModel;
use app\model\User as UserModel;
use app\model\UserAddress as UserAddressModel;
use app\model\UserCoupon as UserCouponModel;
use app\model\VipUser as VipUserModel;
use think\facade\Log;

class MergeService
{
    /**
     * 合并两个用户
     * @param $main_uid --主账号
     * @param $field    --xcx,gzh,app,oponid字段名
     * @param $openid   --需要合并删除的oponId。
     * @param $type --1手机关联，2平台uniobid关联
     * @return string
     */
    public function mergeUser($main_uid, $field, $openid, $type)
    {
    }
}