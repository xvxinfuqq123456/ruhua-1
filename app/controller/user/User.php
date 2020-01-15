<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 9:03
 */

namespace app\controller\user;


use app\model\User as UserModel;
use app\services\TokenService;
use bases\BaseController;

class User extends BaseController
{
    /**
     * 模拟用户登录获取TOKEN
     * @return mixed
     */
    public function userLogin()
    {
        return (new TokenService())->saveCache(['uid' => 15,'openid' => 'oq_jb4mLWx97WOEn7x38yM0YkFhs']);
    }
    
    /**
     * 获取用户基础信息
     */
    public function getInfo()
    {
        $uid = TokenService::getCurrentUid();
        $res=UserModel::with('vip')->field('id,nickname,headpic,mobile',true)->find($uid);
        return app('json')->success($res);
    }
}