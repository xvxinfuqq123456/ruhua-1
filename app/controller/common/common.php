<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/6 0006
 * Time: 15:31
 */

namespace app\controller\common;

use app\services\CommonServices;
use bases\BaseController;

class Common extends BaseController
{
    /**
     * 返回二维码
     * @return string
     */
    public function gitCodeImg()
    {
        $post = input('post.');
        $this->validate($post, ['path' => 'require']);
        return CommonServices::getCodeImg($post);
    }

    /**
     * 获取文件
     * @param $type
     * @return array|false|int
     */
    public function getFile($type)
    {
        $file=[];
        if ($type == 1) {
            $file = readfile("./files/服务协议.txt", "r");
        }
        if ($type == 2) {
            $file = readfile("./files/隐私政策.txt", "r");
        }
        return $file;
    }
}