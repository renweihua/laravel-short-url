<?php

if ( !function_exists('formatting_timestamp') ) {
    //把时间戳转换为几分钟或几小时前或几天前
    function formatting_timestamp($time, $show_second = true): string
    {
        $time = (int) substr($time, 0, 10);
        $int  = time() - $time;
        $str  = '';
        if ($int <= 30) {
            $str = sprintf('刚刚', $int);
        } else if ($int < 60) {
            $str = sprintf('%d秒前', $int);
        } else if ($int < 3600) {
            $str = sprintf('%d分钟前', floor($int / 60));
        } else if ($int < 86400) {
            $str = sprintf('%d小时前', floor($int / 3600));
        } else if ($int < 2592000) {
            $str = sprintf('%d天前', floor($int / 86400));
        } else if (date('Y', $time) == date('Y')) {
            $str = date('m-d H:i' . ($show_second ? ':s' : ''), $time);
        } else {
            $str = date('Y-m-d H:i' . ($show_second ? ':s' : ''), $time);
        }
        return $str;
    }
}
