<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/29 0029
 * Time: 15:41
 */

namespace app\model;


use bases\BaseModel;

class PointsRecord extends BaseModel
{

    public function user(){
        return $this->belongsTo('User','uid','id')->field('id,nickname,headpic,points');
    }

    /**
     * 添加积分变动
     * @param $uid
     * @param $num
     * @return mixed
     */
    public static function addRecord($uid,$num){
        $data['uid']=$uid;
        $data['credittype']='points';
        $data['num']=$num;
        $data['module']='qy2019_shop';
        $data['clerk_type']=1;
        $data['remark']='购买商品获得积分，增加'.$num.'积分';
        $res=self::create($data);
        if(!$res){
            return app('json')->fail();
        }
        return app('json')->success($res['id']);
    }

    /**
     * 使用积分变动
     * @param $uid
     * @param $num
     * @return mixed
     */
    public static function reduce($uid,$num){
        $data['uid']=$uid;
        $data['credittype']='points';
        $data['num']=-$num;
        $data['module']='qy2019_shop';
        $data['clerk_type']=1;
        $data['remark']='使用积分购买商品，减少'.$num.'积分';
        $res=self::create($data);
        if(!$res){
            return app('json')->fail();
        }
        return app('json')->success($res['id']);
    }
}