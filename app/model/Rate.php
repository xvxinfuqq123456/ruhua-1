<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 15:12
 */

namespace app\model;


use bases\BaseModel;

class Rate extends BaseModel
{
    protected $type=['reply_time'=>'timestamp'];
    public function user()
    {
        return $this->belongsTo('User','user_id','id')->field('id,nickname,headpic');
    }

    public function goods()
    {
        return $this->belongsTo('Goods','goods_id','goods_id');
    }

    /**
     * 添加回复
     * @param $post
     * @param  $aid
     * @return mixed
     */
    public static function addReply($post,$aid){
        $data['aid'] = $aid;
        $data['reply_content'] = $post['reply_content'] ;
        $data['reply_time']=time();
        $res=self::where('id',$post['id'])->update($data);
        if($res){
            return app('json')->success();
        }else{
            return app('json')->fail();
        }
    }
}