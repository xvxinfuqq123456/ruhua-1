<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/27 0027
 * Time: 16:58
 */

namespace app\controller\common;


use bases\BaseController;
use app\model\Wntable as WntableModel;

class Wntable extends BaseController
{
    public function add()
    {
        $post = input('post.');
        $type = $post['type'];
        $list = $post['form'];
        $obj=new WntableModel;
        $arr=[];
        for ($i = 0; $i < count($list); $i++) {
            $data = [
                'type1' => $type,
                'desc' => $list[$i]['desc'],
                'type' => $list[$i]['type'],
                'name' => $list[$i]['name'],
                'default' => $list[$i]['default'],
                'options' => $list[$i]['options'],
            ];
            $arr[$i]=$data;
        }
        $res=$obj->insertAll($arr);
        return app('json')->go($res);
    }

    public function getTbByType($type)
    {
        $data = WntableModel::where('type1', $type)->select();
        if(!$data){
            return app('json')->go('不存在');
        }
        for ($i = 0; $i < count($data); $i++) {
            $data[$i]['show'] = true;
            unset($data[$i]['id'], $data[$i]['type1'], $data[$i]['title']);
            $options=[];
            if ($data[$i]['options'] != '') {
                $options = explode('@@', $data[$i]['options']);
                $data[$i]['options'] = $options;
            }
            if ($data[$i]['type'] == 'radio' || $data[$i]['type'] == 'check') {
                $opdata = $data[$i]['options'];
                for ($j = 0; $j < count($opdata); $j++) {
                    $dt['value'] = $j;
                    $dt['name'] = $opdata[$j];
                    $options[$j] = $dt;
                    $dt = null;
                }
                $data[$i]['options'] = $options;
                $opdata = null;
                $options = null;
            }
            if ($data[$i]['type'] == 'date') {
                $data[$i]['options'] = date('Y-m-d');
            }
        }
        return app('json')->success($data);
    }

    public function getAllTb()
    {
        $data = WntableModel::select();
        return app('json')->success($data);
    }

    public function delTb($id)
    {
        $res = WntableModel::destroy($id);
        return app('json')->success($res);
    }

    public function updatetb()
    {
        $post = input('post.');
        $data = [
            'type1' => $post['type1'],
            'desc' => $post['desc'],
            'type' => $post['type'],
            'name' => $post['name'],
            'default' => $post['default'],
            'options' => $post['options']
        ];
        $res = WntableModel::update($data, ['id' => $post['id']]);
        return app('json')->success($res);
    }

}