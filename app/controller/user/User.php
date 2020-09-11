<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 9:03
 */

namespace app\controller\user;


use Aliyun\api_demo\SmsDemo;
use app\model\User as UserModel;
use app\services\TokenService;
use bases\BaseController;
use exceptions\TokenException;
use think\facade\Cache;

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
        $res=UserModel::field('id,nickname,headpic,mobile,web_auth_id as web_auth,invite_code as sfm',true)->find($uid);
        return app('json')->success($res);
    }

    /**
     * 获取发票信息
     * @return mixed
     */
    public function getCpy(){
        $uid = TokenService::getCurrentUid();
        $data= UserModel::getCpyInfo($uid);
        return app('json')->success($data);
    }

    /**
     * 修改
     * @return mixed
     */
    public function editCpy(){
        $uid = TokenService::getCurrentUid();
        $post=input('post.');
        $this->validate($post,['cpy_name'=>'require','cpy_num'=>'require','email'=>'require','user_name'=>'require']);
        return UserModel::editCpy($post,$uid);
    }

    /**
     * 获取小程序码
     * @return mixed
     */
    public function getXcxCode(){
        $uid=TokenService::getCurrentUid();
        $path=input('post.path');
        $scene=input('post.scene');
        return (new UserModel)->getXcxInviteUrl($uid,$path,$scene);
    }

    /**
     * 获取二维码
     * @return mixed
     */
    public function getWebCode(){
        $uid=TokenService::getCurrentUid();
        $path=input('post.path');
        $scene=input('post.scene');
        return (new UserModel)->getWebInviteUrl($uid,$path,$scene);
    }

    public function gzh_bind_code($mobile)
    {
        $uid=TokenService::getCurrentUid();
        $user=UserModel::where('id',$uid)->find();
        Cache::tag('tag')->set('mobile',$mobile);
        if ($user['mobile']){
            throw new TokenException(['msg' => '用户电话已绑定']);
        }
        $code=rand(100000,999999);
        $res=UserModel::update(['gzg_code'=>$code],['id'=>$uid]);
        SmsDemo::sendSms($mobile,$code);
        return app('json')->success();
    }

    public function bind_mobile($code)
    {
        $uid=TokenService::getCurrentUid();
        $user=UserModel::where('id',$uid)->find();
        $mobile=Cache::tag('tag')->get('mobile');
        if(!$mobile){
            throw new TokenException(['msg' => '非法操作']);
        }
        if($user&&$user['gzg_code']==$code){

            $res=UserModel::update(['mobile'=>$mobile,'gzg_code'=>''],['id'=>$uid]);
            Cache::tag('tag')->clear();
            return app('json')->go($res);

        }else{
            throw new TokenException(['msg' => '验证码错误']);
        }
    }

}