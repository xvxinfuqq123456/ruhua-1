<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/18 0018
 * Time: 14:15
 */

namespace app\controller\user;


use app\model\FxAgent as FxAgentModel;
use app\model\FxBind as FxBindModel;
use app\model\FxRecord as FxRecordModel;
use app\services\TokenService;
use bases\BaseController;

class UserFx extends BaseController
{
    public function check_fxagent($uid)
    {
        $agent=FxAgentModel::where('user_id',$uid)->find();
        if(!$agent){
            return app('json')->fail('不是代理商');
        }
    }
    /**
     * 用户查看分销收入统计
     * @return mixed
     */
    public function getFxData(){
        $uid=TokenService::getCurrentUid();
        //$this->check_fxagent($uid);
        return FxRecordModel::getFxMoneyData($uid);
    }

    /**
     * 用户查看申请情况
     * @return mixed
     */
    public function getFxRecord(){
        $uid=TokenService::getCurrentUid();

        //$this->check_fxagent($uid);
        return FxRecordModel::userGetRecord($uid);
    }

    /**
     * 用户查看分销收入明细
     * @return mixed
     */
    public function userRecord(){
        $uid=TokenService::getCurrentUid();

        //$this->check_fxagent($uid);
        return FxRecordModel::userRecord($uid);
    }

    /**
     * 查看排名
     * @return mixed
     */
    public function getFxRank(){
        $uid=TokenService::getCurrentUid();
        //$this->check_fxagent($uid);
        return FxRecordModel::getFxRank($uid);
    }

    public function getBindUser(){
        $uid=TokenService::getCurrentUid();
        //$this->check_fxagent($uid);
        return FxBindModel::getBindUser($uid);
    }

    /**
     * 提交提现申请
     * @return mixed
     */
    public function applyTx(){
        $uid=TokenService::getCurrentUid();
        $ids=input('post.ids');
        if(!$ids) return app('json')->fail('ids必填');
        return FxRecordModel::userApplyTx($uid,$ids);
    }

    /**
     * 手动提现接口
     */

    public function applyApi()
    {
        $data=input('post.');
        $uid=TokenService::getCurrentUid();
        $time=time();
        $list=FxRecordModel::where(['agent_id'=>$uid,'status'=>0])->whereTime('update_time','<',$time)->field('id')->select();
        if(!$list) return app('json')->fail('没有记录');
        $ids=array();
        $i=0;
        foreach ($list as $k=>$v){
            $ids[$i]=$v['id'];
            $i++;
        }
        return FxRecordModel::userApplyTx($uid,$ids,$data);
    }

    /**
     * 获取用户待提现信息
     */
    public function getFxAll()
    {
        $list=FxRecordModel::with(['user'])->where('status',1)->select();
        return $list;

    }

}