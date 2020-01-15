<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/25 0025
 * Time: 10:18
 */

namespace app\controller\auth;


use app\model\User as UserModel;
use exceptions\BaseException;

class AppToken extends Token
{
    //openid，uid放入缓存，$token做缓存键名;
    public function getToken($authResult)
    {
        $openid = $authResult['openid'];
        $unionid = $authResult['unionid'];
        $user = UserModel::where('unionid', $unionid)->find();
        if ($user) {
            $app_id = UserModel::where(['unionid' => $unionid, 'openid_app' => $openid])->find();
            if ($app_id) {
                $uid = $app_id['id'];
            } else {
                $user->save(['openid_app' => $openid]);
                $uid = $user['id'];
            }
        } else {
            $app_id = UserModel::where('openid_app', $openid)->find();
            if ($app_id) {
                $app_id->save(['unionid' => $unionid]);
                $uid = $app_id['id'];
            } else {
                $new_user = UserModel::create([
                    'openid_app' => $openid,
                    'unionid' => $unionid,
                ]);
                $uid = $new_user['id'];
            }
        }
        if (!$uid) {
            throw new BaseException(['msg' => '用户注册失败']);
        }
        $cachedValue = $this->setWxCache($openid, $uid);
        $token = $this->saveCache($cachedValue);
        return $token;
    }

    //组合uid，openid，权限
    private function setWxCache($openid, $uid)
    {
        $cache['openid'] = $openid;
        $cache['uid'] = $uid;
        $cache['scope'] = 9;  // 推荐用枚举
        return $cache;
    }
}