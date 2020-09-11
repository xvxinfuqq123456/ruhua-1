<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020-04-08
 * Time: 17:39
 */

namespace app\controller\common;
use services\LivePlayer;

class Live
{
    public function index($page=0)
    {
        $res=(new LivePlayer)->get_list($page);
        return app('json')->success($res);
    }
}