<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 数字到字母列
 * @author rainfer <81818832@qq.com>
 * @param int
 * @param int
 * @return string
 */
function num2alpha($index, $start = 65)
{
    $str = '';
    if (floor($index / 26) > 0) {
        $str .= num2alpha(floor($index / 26)-1);
    }
    return $str . chr($index % 26 + $start);
}