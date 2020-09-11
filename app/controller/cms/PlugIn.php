<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/15 0015
 * Time: 13:21
 */

namespace app\controller\cms;


use bases\BaseController;

use app\model\RcConfig as RcConfigModel;
use app\model\RcAppli as RcAppliModel;
use app\model\User as UserModel;
use app\model\Company as CompanyModel;
use exceptions\OrderException;

class PlugIn extends BaseController
{
    public function add_config()
    {
        $post=input('post.');
        $rule=[
            'key'=>'require',
            'value'=>'require',
        ];
        $this->validate($post,$rule);
        $reg=RcConfigModel::create($post);
        return app('json')->go($reg);
    }

    public function del_config($key)
    {
       $res=RcConfigModel::where('key',$key)->delete();
       return $res;
    }

    public function get_All()
    {
        $list=RcConfigModel::select();
        return app('json')->go($list);
    }

    public function add_rc_appli()
    {
        $post=input('post.');
        $rule=[
            'user'=>'require',
            'money'=>'require',
        ];
        if($post['type']==1)
        {
            $user=UserModel::where('id',$post['user'])->find();
        }else{
            $user=CompanyModel::where('id',$post['user'])->find();
        }
        if(!$user){
            throw new OrderException(['msg'=>'非法操作']);
        }
        $this->validate($post,$rule);
        $res=RcAppliModel::create($post);
        return app('json')->go($res);
    }




}