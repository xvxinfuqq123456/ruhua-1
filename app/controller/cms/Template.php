<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/30 0030
 * Time: 16:27
 */

namespace app\controller\cms;


use bases\BaseController;
use app\model\Template as TemplateModel;

class Template extends BaseController
{
    public function getAll()
    {
        $list=TemplateModel::select();
        return app('json')->go($list);
    }

    public function add()
    {
        $post=input('post.');
        $rule=[
            'temp_key'=>'require',
            'temp_name'=>'require',
            'content'=>'require',
            'temp_id'=>'require'
        ];
        $this->validate($post,$rule);
        $res=TemplateModel::create($post);
        return app('json')->go($res);

    }
    public function update()
    {
        $post=input('post.');
        $rule=[
            'temp_key'=>'require',
            'temp_name'=>'require',
            'content'=>'require',
            'temp_id'=>'require',
            'id'=>'require'
        ];
        $this->validate($post,$rule);
        $res=TemplateModel::update($post,['id'=>$post['id']]);
        return app('json')->go($res);

    }

    public function del($id)
    {
        $res=TemplateModel::destroy($id);
        return app('json')->go($res);
    }



}